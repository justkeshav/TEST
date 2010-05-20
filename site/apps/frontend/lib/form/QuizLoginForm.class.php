<?php

class QuizLoginForm extends BaseForm
{ 
   public function configure()
   {
      $this->setWidgets(array(
	     'name'  => new sfWidgetFormInputText(array('label'=>'Name')),
	     'email' => new sfWidgetFormInputText(array('label'=>'Email'))
	  ));
	
      $this->setValidators(array(
	     'name'  => new sfValidatorString(array('required' => true)),
	     'email' => new sfValidatorEmail()
	  ));
	
	  $this->widgetSchema->setNameFormat('login[%s]');
   }

}