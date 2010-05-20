[?php

class auto<?php echo $this->getModelClass() ?>Form extends <?php echo $this->getModelClass()."Form\n" ?>
{
   public function setup()
   {
      parent::setup();

<?php if($this->isTimestampable()): ?>
      unset($this['updated_at']);
      unset($this['created_at']);
<?php endif; ?>

      if($this->isNew)
      {
<?php if($this->isDerivative()): ?>
         if(array_key_exists('<?php echo $this->getDerivativeBase() ?>', $this->options))
         {
            $this->getObject()-><?php echo $this->getDerivativeBase() ?> = $this->options['<?php echo $this->getDerivativeBase() ?>'];
            $this->widgetSchema['<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>_id'] = new sfWidgetFormInputHidden(array('default' => $this->getObject()-><?php echo $this->getDerivativeBase() ?>->id));
            $this->validatorSchema['<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>_id'] = new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('<?php echo $this->getDerivativeBase() ?>')));
         }
<?php endif; ?>
<?php if($this->hasParentModel()): ?>
         if(array_key_exists('<?php echo $this->getParentSingularName() ?>', $this->options))
         {
            $this->getObject()-><?php echo $this->getParentForeignKey() ?> = $this->options['<?php echo $this->getParentSingularName() ?>']-><?php echo $this->getParentPrimaryKey() ?>;
         }
<?php endif; ?>
      }
      else
      {
<?php if($this->isTimestampable()): ?>
         $this->widgetSchema['updated_at'] = new ltksfWidgetFormStaticDateTime();
         $this->widgetSchema['created_at'] = new ltksfWidgetFormStaticDateTime();
<?php endif; ?>
      }
<?php if($this->hasParentModel()): ?>

      if(!is_null($this->getObject()-><?php echo $this->getParentForeignKey() ?>))
      {
         // <?php echo $this->getParentSingularName() ?> is known so we dont need to mess with it
         $this->widgetSchema['<?php echo $this->getParentForeignKey() ?>'] = new sfWidgetFormInputHidden();
         $this->setDefault('<?php echo $this->getParentForeignKey() ?>', $this->getObject()-><?php echo $this->getParentForeignKey() ?>);
      }
   <?php if($this->isParentRequired()): ?>
      else
      {
         throw new sfInitializationException('A parent <?php echo $this->getParentSingularName() ?> is required.');
      }
   <?php endif; ?>
<?php endif; ?>
<?php if($this->isDerivative()): ?>

      if(!is_null($this->getObject()-><?php echo $this->getDerivativeBase() ?>))
      {
         $this->widgetSchema['<?php echo $this->getDerivativeBase() ?>'] = new ltksfWidgetFormStaticPartial(array('templateName' => '<?php echo sfInflector::underscore($this->getDerivativeBase()) ?>', 'vars' => array('<?php echo $this->getSingularName() ?>' => $this->getObject())));
      }
      else
      {
         throw new sfInitializationException('A base <?php echo $this->getDerivativeBase() ?> is required.');
      }
<?php endif; ?>
   }
}
