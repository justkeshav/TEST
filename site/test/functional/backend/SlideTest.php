<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new LtkTestFunctionalBackend();
$browser->

info('test basic slide editing')->

   get('/slideshow/21/slide/211/edit')->

   with('request')->begin()->
      isParameter('module', 'slide')->
      isParameter('action', 'edit')->
      isParameter('slideshow_id', 21)->
      isParameter('id', 211)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
   end()->

   click('Save', array('slide' => array(
      'heading' => 'my heading',
      'text' => 'my content'
   )))->

   with('request')->begin()->
      isParameter('module', 'slide')->
      isParameter('action', 'update')->
      isParameter('slideshow_id', 21)->
      isParameter('id', 211)->
   end()->

   with('form')->begin()->
      hasErrors(false)->
   end()->

info('test for availability of revision history')->

   // note that the save click above will create a revision history item, so take that into account when making changes to these tests

   get('/slideshow/21/slide/211/edit')->

   click('History')->

   with('request')->begin()->
      isParameter('module', 'slide')->
      isParameter('action', 'history')->
      isParameter('slideshow_id', 21)->
      isParameter('id', 211)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
      checkElement('#sf_admin_content .sf_admin_list .sf_admin_row.even td.sf_admin_text:nth-child(1)', '/1/')->
   end()->

   click('View', array(), array('position' => 2))->

   with('request')->begin()->
      isParameter('module', 'slide')->
      isParameter('action', 'show')->
      isParameter('slideshow_id', 21)->
      isParameter('id', 211)->
      isParameter('version', 1)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
      checkElement('h1', '/^Revision #1/')->
   end()->

info('test ability to create a new slide')->

   get('/slideshow/21/slide/new')->

   with('request')->begin()->
      isParameter('module', 'slide')->
      isParameter('action', 'new')->
      isParameter('slideshow_id', 21)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
   end()->

   click('Save', array('slide' => array(
      'heading' => 'my heading',
      'id' => null,
      'image_id' => 291,
      'image_link' => null,
      'slideshow_id' => 21,
      'text' => 'my text'
   )))->

   with('request')->begin()->
      isParameter('module', 'slide')->
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
      isParameter('module', 'slide')->
      isParameter('action', 'edit')->
      isParameter('slideshow_id', 21)->
   end()->

   with('response')->begin()->
      isStatusCode(200)->
      checkElement('input[id="slide_heading"][value="my heading"]', true)->
      checkElement('input[id="slide_image_id"][value="291"]', true)->
   end()

;
