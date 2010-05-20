<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new LtkTestFunctionalBackend();
$browser->

info('test basic file editing')->

   get('/channel/2/file/1/edit')->

   with('request')->begin()->
      isParameter('module', 'file')->
      isParameter('action', 'edit')->
      isParameter('channel_id', 2)->
      isParameter('id', 1)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
   end()->

   click('Save', array('file' => array(
      'permission'  => 'my perm',
      'source'      => 'my src',
      'description' => 'my desc'
   )))->

   with('request')->begin()->
      isParameter('module', 'file')->
      isParameter('action', 'update')->
      isParameter('channel_id', 2)->
      isParameter('id', 1)->
   end()->

   with('form')->begin()->
      hasErrors(false)->
   end()

;