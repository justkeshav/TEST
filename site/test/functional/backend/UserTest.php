<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

// test basic user backend
$browser = new LtkTestFunctionalBackend(false);
$browser->
   get('/user/1/edit')->

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

   // successful login should redirect us to original request -- user module, edit action

   with('response')->begin()->
      isStatusCode(302)->
      isRedirected()->
   end()->

   followRedirect()->

   with('user')->begin()->
      isAuthenticated(true)->
   end()->

   with('request')->begin()->
      isParameter('module', 'user')->
      isParameter('action', 'edit')->
   end()->

   // menu and logout link should be available
   with('response')->begin()->
      isStatusCode(200)->
      checkElement('#menu', true)->
      checkElement('#menu a:last-child', 'Logout')->
   end()->
   
   // test user/edit action using ltkadmin user

   with('request')->begin()->
      isParameter('module', 'user')->
      isParameter('action', 'edit')->
      isParameter('id', 1)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
      checkElement('h1','Editing User \'ltkadmin\'')->
   end()->

   // test non-admin user accessing /user module

   click('Logout', array())->

   with('response')->begin()->
      isStatusCode(302)->
      isRedirected()->
   end()->

   followRedirect()->

   click('sign in', array('signin' => array(
      'username' => 'ltkwriter',
      'password' => 'ltk123'
   )))->

   with('request')->begin()->
      isParameter('module', 'sfGuardAuth')->
      isParameter('action', 'signin')->
   end()->

   with('form')->begin()->
      hasErrors(false)->
   end()->

   with('response')->begin()->
      isStatusCode(302)->
      isRedirected()->
   end()->

   followRedirect()->

   with('user')->begin()->
      isAuthenticated(true)->
   end()->

   with('request')->begin()->
      isParameter('module', 'user')->
      isParameter('action', 'edit')->
   end()->

   // menu and logout link should not be available
   with('response')->begin()->
      isStatusCode(200)->
      checkElement('#menu', false)->
   end()

;