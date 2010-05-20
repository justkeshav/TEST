<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

test_standard_conditions('/wiki/Category:The_Lord_of_the_Rings', 'sci-fi');

// basic page content tests
$browser = new LtkTestFunctional('sci-fi');
$browser->
   get('/wiki/Category:The_Lord_of_the_Rings')->

   with('response')->begin()->
      isStatusCode(200)->

      // the h1 should display the title
      checkElement('h1', '/The Lord of the Rings/')->

      // check breadcrumbs
      checkElement('#crumbs li:nth-child(1) a', '/LoveToKnow*/')->
      checkElement('#crumbs li:nth-child(2) a', '/Entertainment & Hobbies*/')->
      checkElement('#crumbs li:nth-child(3) a', '/Sci-Fi*/')->
      checkElement('#crumbs li:nth-child(4)', '/The Lord of the Rings/')->

      // check that an article link is output correctly
      checkElement('.articles ul:nth-child(1) li:nth-child(1) ul li:nth-child(3) a', '/Lord of the Rings: The Return of the King/')->
      checkElement('.articles ul:nth-child(1) li:nth-child(1) ul li:nth-child(3) a[href$="/wiki/Lord_of_the_Rings:_The_Return_of_the_King"]', true)->

      // make sure the article lists are split evenly
      checkElement('.articles ul:nth-child(1) li:nth-child(1) ul li', 3)->
      checkElement('.articles ul:nth-child(2) li:nth-child(1) ul li', 1)->
      checkElement('.articles ul:nth-child(2) li:nth-child(2) ul li', 1)->

      // check for ads
      checkElement('.ads-top script:nth-child(1)', '/289691/')->
      checkElement('.ads-top script:nth-child(3)', '/376179/')->
      checkElement('.ads-bottom', false)->

   end()->

   // check that a link to an aticle is clickable
   click('Lord of the Rings: The Return of the King', array(), array('position' => 1))->
   with('request')->begin()->
      isParameter('url', 'wiki/Lord_of_the_Rings:_The_Return_of_the_King')->
      isParameter('channel', 'sci-fi')->
   end()->
   with('response')->begin()->
      isStatusCode(200)->
   end()
;

// slideshow listing content tests
$browser = new LtkTestFunctional('hair');
$browser->
   get('/Category:Hair_Style_Pictures')->

   with('response')->begin()->
      isStatusCode(200)->

      // make sure the slideshow lists are split evenly
      checkElement('.slideshows a', 5)->
      checkElement('.slideshows > ul', 2)->
      checkElement('.slideshows > h2:nth-child(1)', 'Slideshows')->
      checkElement('.slideshows > ul:nth-child(2) a', 3)->
      checkElement('.slideshows > ul:nth-child(3) a', 2)->

      // check that the slideshows lists are sorted alphabetically
      checkElement('.slideshows > ul:nth-child(2) > li:nth-child(1) > ul:nth-child(1) > li:nth-child(1) > a', 'Beautiful Brunettes')->
      checkElement('.slideshows > ul:nth-child(2) > li:nth-child(1) > ul:nth-child(1) > li:nth-child(2) > a', 'Blonde Hair Styles Gallery')->
      checkElement('.slideshows > ul:nth-child(2) > li:nth-child(2) > ul:nth-child(1) > li:nth-child(1) > a', 'Hair Highlight Examples')->
      checkElement('.slideshows > ul:nth-child(3) > li:nth-child(1) > ul:nth-child(1) > li:nth-child(1) > a', 'Shag Hair Cut Pictures')->
      checkElement('.slideshows > ul:nth-child(3) > li:nth-child(1) > ul:nth-child(1) > li:nth-child(2) > a', 'Short Hair Cut Photos')->

      // check that a slideshow link is output correctly
      checkElement('.slideshows > ul:nth-child(2) > li:nth-child(1) > ul:nth-child(1) > li:nth-child(2) > a[href$="/Slideshow:Blonde_Hair_Styles_Gallery"]', true)->
      checkElement('.slideshows > ul:nth-child(2) > li:nth-child(1) > ul:nth-child(1) > li:nth-child(2) > a[rel*="slideshow"]', true)->

      // check that a not published slideshow doesnt show up
      checkElement('.slideshows a[href$="/Goody_Hair_Accessories"]', false)->

      // check for ads
      checkElement('.ads-top script:nth-child(1)', '/288411/')->
      checkElement('.ads-top script:nth-child(3)', '/376215/')->
      checkElement('.ads-bottom script', '/755234/')->

   end()->

   // check that a link to a slideshow is clickable
   click('Beautiful Brunettes', array(), array('position' => 1))->
   with('request')->begin()->
      isParameter('url', 'Slideshow:Beautiful_Brunettes')->
      isParameter('channel', 'hair')->
      isParameter('page', 0)->
   end()->
   with('response')->begin()->
      isStatusCode(200)->
   end()
;


