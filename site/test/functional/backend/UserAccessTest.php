<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

// test basic slide editing
$browser = new LtkTestFunctionalBackend(false);
$browser->
   get('/vertical')->

   with('user')->begin()->
      isAuthenticated(false)->
   end()->

   // auth should be required and menu should not be shown to non-logged in users
   with('response')->begin()->
      isStatusCode(401)->
      checkElement('#menu', false)->
   end()->

   click('sign in', array('signin' => array(
      'username' => 'ltkadmin',
      'password' => 'ltk123'
   )))->

   with('request')->begin()->
      isParameter('module', 'sfGuardAuth')->
      isParameter('action', 'signin')->
   end()->

   with('form')->begin()->
      hasErrors(false)->
   end()->

   // successful login should redirect us to original request -- vertical index page

   with('response')->begin()->
      isStatusCode(302)->
      isRedirected()->
   end()->

   followRedirect()->

   with('user')->begin()->
      isAuthenticated(true)->
   end()->

   with('request')->begin()->
      isParameter('module', 'vertical')->
      isParameter('action', 'index')->
   end()->

   // menu and logout link should be available
   with('response')->begin()->
      isStatusCode(200)->
      checkElement('#menu', true)->
      checkElement('#menu a:last-child', 'Logout')->
   end()->

   click('Logout')->

   with('user')->begin()->
      isAuthenticated(false)->
   end()
;
