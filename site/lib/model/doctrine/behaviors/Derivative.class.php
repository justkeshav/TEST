<?php

class Derivative extends Doctrine_Template
{
   /**
   * Array of Publishable options
   *
   * @var string
   */
   protected $_options = array(
      'base'    =>  false
   );

   protected $_methods = array();

   /**
   * Set table definition for Publishable behavior
   *
   * @return void
   */
   public function setTableDefinition()
   {
      $baseModel = $this->_options['base'];
      $baseTable = Doctrine::getTable($baseModel);
      $baseIdentifierName = $baseTable->getIdentifier();
      $baseIdentifierDefinition = $baseTable->getDefinitionOf($baseIdentifierName);

      $columnName = Doctrine_Inflector::tableize($baseModel) . '_' . $baseIdentifierName;

      if(!$this->_table->hasColumn($columnName))
      {
         $this->hasColumn($columnName, $baseIdentifierDefinition['type'], $baseIdentifierDefinition['length']);
      }

      if(!$this->_table->hasRelation($baseModel))
      {
         $options = array('local' => $columnName, 'foreign' => $baseIdentifierName, 'autoComplete' => false);
         $this->hasOne($baseModel, $options);
      }

      $this->_table->unshiftFilter(new DerivativeFilter($this->_options));
   }

   public function __call($method, $arguments)
   {
      $invoker = $this->getInvoker();

      // if templates are defined on the model after this one, we want to give preference
      // to their methods before we pass this call to the base model; if we dont do this,
      // this template effectively hides all methods provided by any template added to the
      // model after this one
      if(!in_array($method, $this->_methods))
      {
         // we only need to consider templates added after this one -- templates added
         // before would have already been checked by Doctrine internally
         $found = false;
         foreach ($this->_table->getTemplates() as $template)
         {
            if($found)
            {
               if(is_callable(array($template, $method)))
               {
                  $template->setInvoker($invoker);
                  $this->_table->setMethodOwner($method, $template);
                  return call_user_func_array(array($template, $method), $arguments);
               }
            }
            elseif($template === $this)
            {
               $found = true;
            }
         }
      }

      // this method call was not found onany other templates so it is now safe to call it;
      // we will also cache this fact so we dont have to do the template search the next
      // time this method is called
      $this->_methods[] = $method;
      $model = $this->_options['base'];
      return call_user_func_array(array($invoker->$model, $method), $arguments);
   }
}
