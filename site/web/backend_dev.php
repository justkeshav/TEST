<?php

// this check prevents access to debug front controllers that are deployed by accident to production servers.
$host_parts = explode('.', $_SERVER['HTTP_HOST']);
if($host_parts[count($host_parts) - 1] != 'local' && !in_array($host_parts[count($host_parts) - 4], array('dev', 'test', 'staging')))
{
   die('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}

require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('backend', 'dev', true);
sfContext::createInstance($configuration)->dispatch();
