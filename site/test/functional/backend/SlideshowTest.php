<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

// test basic slide editing
$browser = new LtkTestFunctionalBackend();
$browser->
   get('/title/new')->
   get('/slideshow/21/edit')->

   with('request')->begin()->
      isParameter('module', 'slideshow')->
      isParameter('action', 'edit')->
      isParameter('id', 21)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
   end()->

   click('Save', array('slideshow' => array(
      'show_home_page' => true
   )))->

   with('request')->begin()->
      isParameter('module', 'slideshow')->
      isParameter('action', 'update')->
      isParameter('id', 21)->
   end()->

   with('form')->begin()->
      hasErrors(false)->
   end()->

// test for availability of revision history
// note that the save click above will create a new revision history item, so take that into account when making changes to these tests

   get('/slideshow/21/edit')->

   click('History')->

   with('request')->begin()->
      isParameter('module', 'slideshow')->
      isParameter('action', 'history')->
      isParameter('id', 21)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->

      // testing that the user who made changs shows in revision history
      checkElement('#sf_admin_content .sf_admin_list .sf_admin_row.odd td.sf_admin_text:nth-child(1)', '/2/')->
      checkElement('#sf_admin_content .sf_admin_list .sf_admin_row.odd td.sf_admin_text:nth-child(3)', 'ltkadmin')->

      // testing to see if revision history date shows up
      checkElement('#sf_admin_content .sf_admin_list .sf_admin_row.even td.sf_admin_text:nth-child(1)', '/1/')->
      checkElement('#sf_admin_content .sf_admin_list .sf_admin_row.even td.sf_admin_text:nth-child(2)', '2008-11-24 23:40:35')->
   end()->

   click('View', array(), array('position' => 1))->

   with('request')->begin()->
      isParameter('module', 'slideshow')->
      isParameter('action', 'show')->
      isParameter('id', 21)->
      isParameter('version', 2)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
      checkElement('h1', '/^Revision #2/')->
   end()
;
