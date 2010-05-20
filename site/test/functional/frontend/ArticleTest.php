<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

test_standard_conditions('/wiki/Dr._Beverly_Crusher', 'sci-fi');

// basic page content tests
$browser = new LtkTestFunctional('sci-fi');
$browser->
   get('/wiki/Dr._Beverly_Crusher')->

   with('response')->begin()->

      // the h1 should display the title
      checkElement('h1', '/Dr. Beverly Crusher/')->

      // check breadcrumbs
      checkElement('#crumbs li:nth-child(1) a', '/LoveToKnow*/')->
      checkElement('#crumbs li:nth-child(2) a', '/Entertainment & Hobbies*/')->
      checkElement('#crumbs li:nth-child(3) a', '/Sci-Fi*/')->
      checkElement('#crumbs li:nth-child(4) a', '/Star Trek*/')->
      checkElement('#crumbs li:nth-child(4) a[href$="/wiki/Category:Star_Trek"]', true)->
      checkElement('#crumbs li:nth-child(5)', '/Dr. Beverly Crusher/')->

      // check for ads
      checkElement('.ads-bottom script', '/315991/')->

   end()->

   // check that the breadcrumb link to the parent cat is clickable
   click('Star Trek', array(), array('position' => 1))->
   with('request')->begin()->
      isParameter('url', 'wiki/Category:Star_Trek')->
      isParameter('channel', 'sci-fi')->
   end()->
   with('response')->begin()->
      isStatusCode(200)->
   end()
;

// chitika ad test
$browser = new LtkTestFunctional('hair');
$browser->
   get('/Half_Ponytail')->
   with('response')->begin()->
      isStatusCode(200)->
      checkElement('.ads-bottom script', '/755234/')->
   end()
;
