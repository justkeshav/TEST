<?php

class TitleActivateForm extends sfForm
{
   public function configure()
   {
      $this->setWidgets(array(
         'title_ids' => new sfWidgetFormInputHidden(),
         'available_on' => new sfWidgetFormDate(array(
               'years' => range(date('Y'), date('Y') + 9),              
               'can_be_empty' => false,
               ))));
      
    $this->setValidators(array(
      'title_ids'    => new sfValidatorString(array('required' => true)),   
      'available_on'    => new sfValidatorString(array('required' => true))));    
   }
}