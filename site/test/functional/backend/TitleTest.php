<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

// test basic title creation
$browser = new LtkTestFunctionalBackend();
$browser->
   get('/title/new')->

   with('request')->begin()->
      isParameter('module', 'title')->
      isParameter('action', 'new')->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
   end()->

   click('Save', array('title' => array(
      'type' => 'Category',
      'title' => 'My Spiffy Category Title',
      'channel_id' => 34,
      'categories_list' => array(46)
   )))->

   with('request')->begin()->
      isParameter('module', 'title')->
      isParameter('action', 'create')->
   end()->

   with('form')->begin()->
      hasErrors(false)->
   end()->

   with('response')->begin()->
      isStatusCode(302)->
      isRedirected()->
   end()->

   followRedirect()->

   with('request')->begin()->
      isParameter('module', 'title')->
      isParameter('action', 'edit')->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
      checkElement('input[id="title_url"][value="lord-rings/my-spiffy-category-title"]', true)->
   end()
;
