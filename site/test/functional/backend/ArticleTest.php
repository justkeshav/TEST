<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

// test basic article editing
$browser = new LtkTestFunctionalBackend();
$browser->
   get('/article/20/edit')->

   with('request')->begin()->
      isParameter('module', 'article')->
      isParameter('action', 'edit')->
      isParameter('id', 20)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
   end()->

   click('Save', array('article' => array(
      'text' => 'new article text',
   )))->

   with('request')->begin()->
      isParameter('module', 'article')->
      isParameter('action', 'update')->
      isParameter('id', 20)->
   end()->

   with('form')->begin()->
      hasErrors(false)->
   end()->

// test for availability of revision history
// note that the save click above will create a revision history item for the related Title, so take that into account when making changes to these tests

   get('/article/20/edit')->

   click('History')->

   with('request')->begin()->
      isParameter('module', 'article')->
      isParameter('action', 'history')->
      isParameter('id', 20)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->

      // testing to see if revision history date shows up
      checkElement('#sf_admin_content .sf_admin_list .sf_admin_row.even td.sf_admin_text:nth-child(1)', '/1/')->
      checkElement('#sf_admin_content .sf_admin_list .sf_admin_row.even td.sf_admin_text:nth-child(2)', '2010-01-04 01:02:21')->
   end()->

   click('View', array(), array('position' => 1))->

   with('request')->begin()->
      isParameter('module', 'article')->
      isParameter('action', 'show')->
      isParameter('id', 20)->
      isParameter('version', 2)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
      checkElement('h1', '/^Revision #2/')->
   end()
;