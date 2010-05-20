<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

// test basic channel editing
$browser = new LtkTestFunctionalBackend();
$browser->
   get('/channel/2/edit')->

   with('request')->begin()->
      isParameter('module', 'channel')->
      isParameter('action', 'edit')->
      isParameter('id', 2)->
   end()->

   with('response')->begin()->
      checkElement('input[id="channel_settings_ad_sidebar"][value="111111"]', true)->
   end()->

   click('Save', array('channel' => array(
      'settings_ad_sidebar' => '987654'
   )))->

   with('request')->begin()->
      isParameter('module', 'channel')->
      isParameter('action', 'update')->
      isParameter('id', 2)->
   end()->

   with('form')->begin()->
      hasErrors(false)->
   end()->

   with('response')->begin()->
      isRedirected()->
   end()->

   followRedirect()->

   with('request')->begin()->
      isParameter('module', 'channel')->
      isParameter('action', 'edit')->
      isParameter('id', 2)->
   end()->

   with('response')->begin()->
      checkElement('input[id="channel_settings_ad_sidebar"][value="987654"]', true)->
   end()
;
