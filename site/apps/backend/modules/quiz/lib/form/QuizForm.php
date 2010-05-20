<?php

class BackendQuizForm extends autoQuizForm
{
   public function configure()
   {		
      $this->widgetSchema['format'] = new sfWidgetFormChoice(array('choices' => array(
         Quiz::TYPE_QUIZ   => 'Quiz',
         Quiz::TYPE_POLL   => 'Poll'
      )));

      $this->widgetSchema['result_format'] = new sfWidgetFormChoice(array('choices' => array(
         Quiz::RESULT_AFTER   => 'After Each Answer',
         Quiz::RESULT_END     => 'At the End',
         Quiz::RESULT_BOTH    => 'Both',
         Quiz::RESULT_EMAIL   => 'Via Email',
      )));

      $this->widgetSchema['result_type'] = new sfWidgetFormChoice(array('choices' => array(
	     Quiz::SHOW_RESULTS  => 'Show All User Responses',
	     Quiz::SHOW_VALID    => 'Show Correct Answer'),
	     'expanded' => true
	  ));
	
	  $this->setDefault('result_type', Quiz::SHOW_RESULTS);
		
      $this->widgetSchema['login_position'] = new sfWidgetFormChoice(array('choices' => array(
         Quiz::LOGIN_ANON     => 'Anonymous',
         Quiz::LOGIN_REQUIRED => 'Login Required',
         Quiz::LOGIN_OPTIONAL => 'Login Optional',
         Quiz::LOGIN_AFTER    => 'Login After Quiz',
         Quiz::LOGIN_RESULT   => 'Login After Quiz, Before Result'
      )));
   }
}
