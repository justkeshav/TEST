<?php

require_once(dirname(__FILE__).'/../../../../../plugins/sfDoctrineGuardPlugin/modules/sfGuardAuth/lib/BasesfGuardAuthActions.class.php');

class sfGuardAuthActions extends BasesfGuardAuthActions
{
  public function executeSignout($request)
  {
	  // reset backend module filters
	  $this->getUser()->getAttributeHolder()->remove('article.filters', null, 'admin_module');
	  $this->getUser()->getAttributeHolder()->remove('title.filters', null, 'admin_module');
      $this->getUser()->getAttributeHolder()->remove('slideshow.filters', null, 'admin_module');	
      $this->getUser()->getAttributeHolder()->remove('quiz.filters', null, 'admin_module');		

    parent::executeSignout($request);
  }
}
