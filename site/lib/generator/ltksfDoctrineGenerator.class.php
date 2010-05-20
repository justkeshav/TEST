<?php

class ltksfDoctrineGenerator extends sfDoctrineGenerator
{
   /**
   * Initializes the current sfGenerator instance.
   *
   * @param sfGeneratorManager $generatorManager A sfGeneratorManager instance
   */
   public function initialize(sfGeneratorManager $generatorManager)
   {
      parent::initialize($generatorManager);

      $this->setGeneratorClass('ltksfDoctrineModule');
   }

   /**
   * Generates classes and templates in cache.
   *
   * @param array $params The parameters
   *
   * @return string The data to put in configuration cache
   */
   public function generate($params = array())
   {
      $retVal = parent::generate($params);

      // move form class file
      if (file_exists($file = $this->generatorManager->getBasePath().'/'.$this->getGeneratedModuleName().'/lib/form/form.class.php'))
      {
         $formFile = $this->getGeneratedModuleName().'/lib/form/auto'.$this->getModelClass().'Form.class.php';
         @rename($file, $this->generatorManager->getBasePath().'/'.$formFile);
         $retVal .= "\nrequire_once(sfConfig::get('sf_module_cache_dir').'/".$formFile."');";
      }

      // rename derivatie base partial if needed or delete it
      if (file_exists($file = $this->generatorManager->getBasePath().'/'.$this->getGeneratedModuleName().'/templates/_derivative_base.php'))
      {
         if($this->isDerivative())
         {
            $derFile = $this->getGeneratedModuleName().'/templates/_'.sfInflector::underscore($this->getDerivativeBase()).'.php';
            @rename($file, $this->generatorManager->getBasePath().'/'.$derFile);
         }
         else
         {
            @unlink($this->generatorManager->getBasePath().'/'.$this->getGeneratedModuleName().'/templates/_derivative_base.php');
         }
      }

      return $retVal;
   }

   public function hasParentModel()
   {
      return isset($this->params['parent']['model']);
   }

   public function isParentRequired()
   {
      return !isset($this->params['parent']['required']) || $this->params['parent']['required'] !== false;
   }

   public function getParentModelClass()
   {
      return $this->params['parent']['model'];
   }

   public function getParentSingularName()
   {
      return isset($this->params['parent']['singular']) ? $this->params['parent']['singular'] : sfInflector::underscore($this->getParentModelClass());
   }

   public function getParentPrimaryKey()
   {
      return isset($this->params['parent']['primary']) ? $this->params['parent']['primary'] : 'id';
   }

   public function getParentForeignKey()
   {
      return isset($this->params['parent']['foreign']) ? $this->params['parent']['foreign'] : sfInflector::underscore($this->getParentModelClass()) . '_id';
   }

   public function isTimestampable()
   {
      return $this->table->hasTemplate('Timestampable');
   }

   public function isDerivative()
   {
      return $this->table->hasTemplate('Derivative');
   }

   public function getDerivativeBase()
   {
      return $this->table->getTemplate('Derivative')->getOption('base');
   }

   public function isAuditable()
   {
      return $this->table->hasTemplate('Auditable');
   }
}
