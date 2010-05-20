<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

// test basic title creation
   $browser = new LtkTestFunctionalBackend();
   $browser->setHttpHeader('X_REQUESTED_WITH', 'XMLHttpRequest');
   $browser->
      get('/title/categoryList?channel_id=34')->
      with('response')->begin()->
         matches('/Star Trek/')->
   end()
;