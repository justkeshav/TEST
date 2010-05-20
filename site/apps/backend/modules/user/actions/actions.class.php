<?php

require_once dirname(__FILE__).'/../lib/userGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/userGeneratorHelper.class.php';

/**
 * user actions.
 *
 * @package    LoveToKnow
 * @subpackage user
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class userActions extends autoUserActions
{
   public function execute($request)
   {
	  $response = $this->getResponse();
	  $response->addJavaScript('user');

      parent::execute($request);
   }

   protected function buildQuery()
   {
      $query = parent::buildQuery();

      // non-admins can only see users w/ role = Writer
      if(!$this->getUser()->getGuardUser()->isAdmin())
      {
	     $query->addFrom('r.sfGuardUserGroup role')->Where('role.group_id = ?', sfGuardUser::ROLE_WRITER);
      }

      return $query;
   }
}

