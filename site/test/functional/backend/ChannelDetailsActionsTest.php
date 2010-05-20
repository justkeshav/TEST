<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new LtkTestFunctionalBackend();

$browser->
  get('/ChannelDetails/new?channel_id=29')->
  with('request')->begin()->
    isParameter('module', 'ChannelDetails')->
    isParameter('action', 'new')->
    isParameter('channel_id', '29')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
  end()->
  click('Save',array('channel_details' => array(
      'channel_id' => 29,
      'highlight_title_id' => 10868,
      'highlight_content' => 'asasas',
      'popular1_title_id' => 10868,
      'popular1_content' => 'asasas',
      'popular2_title_id' => 10868,
      'popular2_content' => 'asasas',
      'about_title_id' => 10868
   )))->

   with('request')->begin()->
      isParameter('module', 'ChannelDetails')->
      isParameter('action', 'create')->
   end()->

   with('form')->begin()->
      hasErrors(false)->
   end()->

  get('/ChannelDetails/29/edit')->
  with('request')->begin()->
    isParameter('module', 'ChannelDetails')->
    isParameter('action', 'edit')->
  end()->
  
  with('response')->begin()->
    isStatusCode(200)->
  end()
;
