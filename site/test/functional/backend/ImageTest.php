<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new LtkTestFunctionalBackend();
$browser->

info('test basic image editing')->

   get('/channel/29/image/777/edit')->

   with('request')->begin()->
      isParameter('module', 'image')->
      isParameter('action', 'edit')->
      isParameter('channel_id', 29)->
      isParameter('id', 777)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
   end()->

   click('Save', array('image' => array(
      'permission'  => 'my perm',
      'source'      => 'my src',
      'source_url'  => '',
      'description' => 'my desc'
   )))->

   with('request')->begin()->
      isParameter('module', 'image')->
      isParameter('action', 'update')->
      isParameter('channel_id', 29)->
      isParameter('id', 777)->
   end()->

   with('form')->begin()->
      hasErrors(false)->
   end()->

info('make sure image browser for auto complete works')->

   get('/channel/29/image_browse?term=jpg')->

   with('request')->begin()->
      isParameter('module', 'image')->
      isParameter('action', 'browse')->
      isParameter('channel_id', 29)->
      isParameter('term', 'jpg')->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
      isHeader('content-type', 'application/json')->
   end()

;
