<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

// test basic category editing
$browser = new LtkTestFunctionalBackend();
$browser->
   get('/category/21/edit')->

   with('request')->begin()->
      isParameter('module', 'category')->
      isParameter('action', 'edit')->
      isParameter('id', 21)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
   end()->

   click('Save')->

   with('request')->begin()->
      isParameter('module', 'category')->
      isParameter('action', 'update')->
      isParameter('id', 21)->
   end()->

   with('form')->begin()->
      hasErrors(false)->
   end()->

// test for availability of revision history
// note that the save click above will create a revision history item for the related Title, so take that into account when making changes to these tests

   get('/category/21/edit')->

   click('History')->

   with('request')->begin()->
      isParameter('module', 'category')->
      isParameter('action', 'history')->
      isParameter('id', 21)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->

   end()
;