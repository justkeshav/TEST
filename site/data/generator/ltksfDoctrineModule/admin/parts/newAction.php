   public function executeNew(sfWebRequest $request)
   {
      $options = array();
<?php if($this->isDerivative()): ?>
      $options['<?php echo $this->getDerivativeBase() ?>'] = $this-><?php echo $this->getDerivativeBase() ?>;
<?php endif; ?>
<?php if($this->hasParentModel()): ?>
      $options['<?php echo $this->getParentSingularName() ?>'] = $this-><?php echo $this->getParentSingularName() ?>;
<?php endif; ?>
      $this->form = $this->configuration->getForm(null, $options);
      $this-><?php echo $this->getSingularName() ?> = $this->form->getObject();
   }
