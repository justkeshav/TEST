<?php

require_once dirname(__FILE__).'/../lib/quiz_contentGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/quiz_contentGeneratorHelper.class.php';

/**
 * quiz_content actions.
 *
 * @package    LoveToKnow
 * @subpackage quiz_content
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class quiz_contentActions extends autoQuiz_contentActions
{
   public function execute($request)
   {
	  $response = $this->getResponse();
	  $response->addStyleSheet('quiz');

      parent::execute($request);
   }

   protected function buildQuery()
   {
      return parent::buildQuery()->addWhere('quiz_id = ?', $this->quiz->id)->addWhere('parent_id = 0');
   } 
}
