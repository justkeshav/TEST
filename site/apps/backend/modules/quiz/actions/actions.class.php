<?php

require_once dirname(__FILE__).'/../lib/quizGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/quizGeneratorHelper.class.php';

/**
 * quiz actions.
 *
 * @package    LoveToKnow
 * @subpackage quiz
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class quizActions extends autoQuizActions
{
   public function execute($request)
   {
	  $response = $this->getResponse();
	  $response->addStyleSheet('quiz');
	  $response->addJavaScript('quiz');

      parent::execute($request);
   }	

   public function executeQuestions(sfWebRequest $request)
   {
      $this->redirect('@quiz_content?quiz_id=' . $request->getParameter('id')); 
   }

   public function executeSummary(sfWebRequest $request)
   {
      $this->quiz = $this->getRoute()->getObject();
      $this->quiz->setPage(-1); 
   }
}
