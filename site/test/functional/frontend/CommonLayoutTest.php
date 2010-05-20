<?php

require_once(dirname(__FILE__).'/../../bootstrap/functional.php');

$browser = new LtkTestFunctional('hair');

$browser->
   get('/Half_Ponytail')->

   with('response')->begin()->

      info('Header')->

      // check that the major elements exist
      checkElement('#header', 1)->
      checkElement('#logo', 1)->
      checkElement('#tagline', 1)->
      checkElement('#menu', 1)->

      // first menu should not have any items because it just goes to the home page
      checkElement('#menu li:first-child ul', false)->

      // all other menus should have at least 2 items in their list
      checkElement('#menu li:nth-child(2) ul li:nth-child(2)', true)->
      checkElement('#menu li:nth-child(3) ul li:nth-child(2)', true)->
      checkElement('#menu li:last-child ul li:nth-child(2)', true)->

      // and there should be no sub-menus
      checkElement('#menu li ul ul', false)->

      info('Sidebar')->

      // check for categories list and make sure in alpha order
      checkElement('#sidebar .section.categories li:nth-child(1) a', 'About Hair')->
      checkElement('#sidebar .section.categories li:nth-child(5) a', 'Hair Styling Equipment')->

      // check for ad
      checkElement('#sidebar .section.ad script', '/288413/')->

      // check for tools
      checkElement('#sidebar .section.tools')->

      info('Footer')->

      // check that the major elements exist
      checkElement('#footer .links', 1)->
      checkElement('#footer .legal', 1)->

   end()
;

