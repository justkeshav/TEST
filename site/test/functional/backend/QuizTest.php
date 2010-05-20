<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

// test basic quiz editing
$browser = new LtkTestFunctionalBackend();
$browser->
   get('/title/new')->
   get('/quiz/10/edit')->

   with('request')->begin()->
      isParameter('module', 'quiz')->
      isParameter('action', 'edit')->
      isParameter('id', 10)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
   end()->

   click('Save', array('quiz' => array(
	  //'Title' => array('title' => 'my title', 'slug' => 'my slug'),
      'description1' => 'description for head of quiz',
      'description2' => 'description for footer of quiz',
      'message'      => 'message to display at end of quiz'
   )))->

   with('request')->begin()->
      isParameter('module', 'quiz')->
      isParameter('action', 'update')->
      isParameter('id', 10)->
   end()->

   with('form')->begin()->
      hasErrors(false)->
   end()->

// test for availability of revision history
// note that the save click above will create a revision history item for the related Title, so take that into account when making changes to these tests

   get('/quiz/10/edit')->

   click('History')->

   with('request')->begin()->
      isParameter('module', 'quiz')->
      isParameter('action', 'history')->
      isParameter('id', 10)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->

      // testing to see if revision history date shows up
      checkElement('#sf_admin_content .sf_admin_list .sf_admin_row.even td.sf_admin_text:nth-child(1)', '/1/')->
      checkElement('#sf_admin_content .sf_admin_list .sf_admin_row.even td.sf_admin_text:nth-child(2)', '2010-03-15 23:19:34')->
   end()->

   click('View', array(), array('position' => 1))->

   with('request')->begin()->
      isParameter('module', 'quiz')->
      isParameter('action', 'show')->
      isParameter('id', 10)->
      isParameter('version', 2)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
      checkElement('h1', '/^Revision #2/')->
   end()
;