<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

test_standard_conditions('/Slideshow:Beautiful_Brunettes', 'hair');

// basic & first slide content tests
$browser = new LtkTestFunctional('hair');
$browser->
   get('/Slideshow:Beautiful_Brunettes')->

   with('response')->begin()->
      isStatusCode(200)->

      // the h1 should display the title
      checkElement('h1', 'Beautiful Brunettes')->

      // the h2 should show the slide heading
      checkElement('#content h2', 'Brunette Glamour')->

      // check image is displayed -- we dont know id, but this is close enough
      checkElement('#image img[src^="http://hair.lovetoknow.local/images/slide/"]', true)->
      checkElement('#image img[src$="-brownbeauty10.jpg"]', true)->

      // check nav links
      checkElement('#buttons a:nth-child(2)', '/Last/')->
      checkElement('#buttons a[href$="/Slideshow:Beautiful_Brunettes~12"]:nth-child(2)', true)->
      checkElement('#buttons a:nth-child(4)', '/Next/')->
      checkElement('#buttons a[href$="/Slideshow:Beautiful_Brunettes~1"]:nth-child(4)', true)->

   end()
;

// middle slide content tests
$browser = new LtkTestFunctional('hair');
$browser->
   get('/Slideshow:Beautiful_Brunettes~2')->

   with('response')->begin()->
      isStatusCode(200)->

      // the h1 should display the title
      checkElement('h1', 'Beautiful Brunettes')->

      // the h2 should show the slide heading
      checkElement('#content h2', 'Natural Highlights')->

      // check image is displayed -- we dont know id, but this is close enough
      checkElement('#image img[src^="http://hair.lovetoknow.local/images/slide/"]', true)->
      checkElement('#image img[src$="-brownbeauty13.jpg"]', true)->

      // check nav links
      checkElement('#buttons a:nth-child(2)', '/Previous/')->
      checkElement('#buttons a[href$="/Slideshow:Beautiful_Brunettes~1"]:nth-child(2)', true)->
      checkElement('#buttons a:nth-child(4)', '/Next/')->
      checkElement('#buttons a[href$="/Slideshow:Beautiful_Brunettes~3"]:nth-child(4)', true)->

   end()
;

// last slide content tests
$browser = new LtkTestFunctional('hair');
$browser->
   get('/Slideshow:Beautiful_Brunettes~12')->

   with('response')->begin()->
      isStatusCode(200)->

      // the h1 should display the title
      checkElement('h1', 'Beautiful Brunettes')->

      // the h2 should show the slide heading
      checkElement('#content h2', 'Medium Layers')->

      // check image is displayed -- we dont know id, but this is close enough
      checkElement('#image img[src^="http://hair.lovetoknow.local/images/slide/"]', true)->
      checkElement('#image img[src$="-brownbeauty5.jpg"]', true)->

      // check nav links
      checkElement('#buttons a:nth-child(2)', '/Previous/')->
      checkElement('#buttons a[href$="/Slideshow:Beautiful_Brunettes~11"]:nth-child(2)', true)->
      checkElement('#buttons a:nth-child(4)', '/Replay/')->
      checkElement('#buttons a[href$="/Slideshow:Beautiful_Brunettes"]:nth-child(4)', true)->

   end()
;

// TODO: re-enable after publishing is re-implemented
/*
// non-published slideshow should not be available
$browser = new LtkTestFunctional('hair');
$browser->
   get('/Goody_Hair_Accessories')->
   with('response')->begin()->
      isStatusCode(404)->
   end()
;
*/
