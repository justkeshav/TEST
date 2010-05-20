[?php

/**
 * <?php echo $this->getModuleName() ?> module configuration.
 *
 * @package    ##PROJECT_NAME##
 * @subpackage <?php echo $this->getModuleName()."\n" ?>
 * @author     ##AUTHOR_NAME##
 * @version    SVN: $Id: configuration.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class Base<?php echo ucfirst($this->getModuleName()) ?>GeneratorConfiguration extends sfModelGeneratorConfiguration
{
<?php include dirname(__FILE__).'/actionsConfiguration.php' ?>

<?php include dirname(__FILE__).'/fieldsConfiguration.php' ?>

  /**
   * Gets the form class name.
   *
   * @return string The form class name
   */
  public function getFormClass()
  {
    return '<?php echo isset($this->config['form']['class']) ? $this->config['form']['class'] : 'auto'.$this->getModelClass().'Form' ?>';
<?php unset($this->config['form']['class']) ?>
  }

<?php if($this->isDerivative()): ?>
  public function getDerivativeBaseFormClass()
  {
    return '<?php echo isset($this->config['form']['derivative_base_class']) ? $this->config['form']['derivative_base_class'] : $this->getDerivativeBase().'Form' ?>';
<?php unset($this->config['form']['derivative_base_class']) ?>
  }
<?php endif; ?>

  public function hasFilterForm()
  {
    return <?php echo !isset($this->config['filter']['class']) || false !== $this->config['filter']['class'] ? 'true' : 'false' ?>;
  }

  /**
   * Gets the filter form class name
   *
   * @return string The filter form class name associated with this generator
   */
  public function getFilterFormClass()
  {
    return '<?php echo isset($this->config['filter']['class']) && !in_array($this->config['filter']['class'], array(null, true, false), true) ? $this->config['filter']['class'] : $this->getModelClass().'FormFilter' ?>';
<?php unset($this->config['filter']['class']) ?>
  }

<?php include dirname(__FILE__).'/paginationConfiguration.php' ?>

<?php include dirname(__FILE__).'/sortingConfiguration.php' ?>

  public function getTableMethod()
  {
    return '<?php echo isset($this->config['list']['table_method']) ? $this->config['list']['table_method'] : null ?>';
<?php unset($this->config['list']['table_method']) ?>
  }

  public function getTableCountMethod()
  {
    return '<?php echo isset($this->config['list']['table_count_method']) ? $this->config['list']['table_count_method'] : null ?>';
<?php unset($this->config['list']['table_count_method']) ?>
  }

<?php if($this->isAuditable()): ?>
   public function getHistoryTitle()
   {
      return '<?php echo $this->escapeString(isset($this->config['history']['title']) ? $this->config['history']['title'] : sfInflector::humanize($this->getModuleName()).' History') ?>';
<?php unset($this->config['history']['title']) ?>
   }

   protected function compile()
   {
      parent::compile();

      $config = $this->getConfig();

      $this->configuration = array_merge($this->configuration, array(
         'history' => array('title' => $this->getHistoryTitle()),
         'show' => array('fields' => array())
      ));

      foreach (array_keys($config['default']) as $field)
      {
         $formConfig = array_merge($config['default'][$field], isset($config['form'][$field]) ? $config['form'][$field] : array());
         $this->configuration['show']['fields'][$field] = new sfModelGeneratorConfigurationField($field, array_merge($formConfig, isset($config['show'][$field]) ? $config['show'][$field] : array()));
      }

      $this->parseVariables('history', 'title');
   }

   /**
   * Gets the fields that represents the model.
   *
   * If no show.display parameter is passed in the configuration,
   * all the fields from the model are returned (dynamically).
   */
   public function getShowFields(Doctrine_Record $object, $revision)
   {
      $table = $object->getTable();

      $config = $this->getConfig();

      $fieldsets = $this->getShowDisplay();

      if($fieldsets)
      {
         // with fieldsets?
         if(!is_array(reset($fieldsets)))
         {
            $fieldsets = array('NONE' => $fieldsets);
         }
      }
      else
      {
         $fields = array();

         foreach($table->getFieldNames() as $name)
         {
            switch($name)
            {
               case 'id':
               case 'version':
               case 'updated_at':
               case 'updated_by':
               case 'created_at':
               case 'created_by':
                  break;

               default:
                  $fields[] = $name;
            }
         }

         $fieldsets = array('NONE' => $fields, 'Info' => array('Created', 'Updated'));
      }

      $fields = array();

      foreach($fieldsets as $fieldset => $names)
      {
         if(!$names)
         {
            continue;
         }

         $fields[$fieldset] = array();

         foreach($names as $name)
         {
            $extraConfig = array();
            
            list($name, $flag) = sfModelGeneratorConfigurationField::splitFieldWithFlag($name);

            if(!isset($this->configuration['show']['fields'][$name]))
            {
               $this->configuration['show']['fields'][$name] = new sfModelGeneratorConfigurationField($name, array_merge(
                  isset($config['show'][$name]) ? $config['show'][$name] : array(),
                  array('is_real' => false, 'type' => 'Text', 'flag' => $flag)
               ));
            }

            $field = $this->configuration['show']['fields'][$name];
            $field->setFlag($flag);

            if($field->getType() == 'ForeignKey')
            {
               foreach($table->getRelations() as $relation)
               {
                  $local = (array) $relation['local'];
                  $local = array_map('strtolower', $local);
                  if(in_array(strtolower($name), $local))
                  {
                     $relation = Doctrine::getTable($relation['class'])->find($revision[$name]);
                     $extraConfig = array('relation' => "$relation");
                     break;
                  }
               }
            }
            else
            {
               switch($name)
               {
                  case 'Created':
                     if($user = Doctrine::getTable('sfGuardUser')->find($revision['created_by']))
                     {
                        $user = $user->toArray();
                     }
                     $extraConfig = array('type' => 'Audit', 'date' => $revision['created_at'], 'user' => $user);
                     break;

                  case 'Updated':
                     if($user = Doctrine::getTable('sfGuardUser')->find($revision['updated_by']))
                     {
                        $user = $user->toArray();
                     }
                     $extraConfig = array('type' => 'Audit', 'date' => $revision['updated_at'], 'user' => $user);
                     break;
               }
            }

            $fields[$fieldset][$name] = new sfModelGeneratorConfigurationField($name, array_merge($field->getConfig(), $extraConfig));
         }
      }

      return $fields;
   }
<?php endif; ?>
}
