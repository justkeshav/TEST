<?php

class backendConfiguration extends sfApplicationConfiguration
{
  public function configure()
  {
  }
  
  public function initialize()
  { 
      session_set_cookie_params(0, '/', ".".sfConfig::get('app_host'));
      parent::initialize();

  }
}
