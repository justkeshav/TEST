<?php

include(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new LtkTestFunctionalBackend();

$browser->
  get('/FeatureArticle/new?channel_id=29')->
  with('request')->begin()->
    isParameter('module', 'FeatureArticle')->
    isParameter('action', 'new')->
    isParameter('channel_id', '29')->
  end()->

  with('response')->begin()->
    isStatusCode(200)->
  end()
;
