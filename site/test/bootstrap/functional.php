<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once dirname(__FILE__).'/common.php';

// guess current application
if (!isset($app))
{
  $traces = debug_backtrace();
  $caller = $traces[0];

  $dirPieces = explode(DIRECTORY_SEPARATOR, dirname($caller['file']));
  $app = array_pop($dirPieces);
}

require_once dirname(__FILE__).'/../../config/ProjectConfiguration.class.php';
$configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', isset($debug) ? $debug : true);
sfContext::createInstance($configuration);

// remove all cache
sfToolkit::clearDirectory(sfConfig::get('sf_app_cache_dir'));

new sfDatabaseManager($configuration);

load_test_data();

class LtkTestFunctional extends sfTestFunctional
{
   /**
   * @param mixed $subdomain Subdomain to browse, default is www
   * @return LtkTestFunctional
   */
   function __construct($subdomain = '')
   {
      $host = sfConfig::get('app_host');

      if(!empty($subdomain))
      {
         $host = "$subdomain.$host";
      }

      parent::__construct(new sfBrowser($host));
   }
}

class LtkTestFunctionalBackend extends LtkTestFunctional
{
   function __construct($shouldLogin = true)
   {
      parent::__construct('admin');

      if($shouldLogin)
      {
         backend_login_admin($this);
      }
   }
}

function test_expect_not_found($url, $channel = '')
{
   $browser = new LtkTestFunctional($channel);
   $browser->
      get($url)->
      with('response')->begin()->isStatusCode(404)->end()
   ;
}

function test_standard_conditions($url, $channel)
{
   // make sure we dont get a successful response for the root site
   test_expect_not_found($url);

   // make sure we dont get a successful response for the admin site
   test_expect_not_found($url, 'admin');

   switch($channel)
   {
      case 'www':
         // make sure we dont get a successful response for a channel
         test_expect_not_found($url, 'sci-fi');
         break;

      case 'sci-fi':
         // make sure we dont get a successful response for a different channel
         test_expect_not_found($url, 'hair');

         // make sure we dont get a successful response for the www site
         test_expect_not_found($url, 'www');
         break;

      default:
         // make sure we dont get a successful response for a different channel
         test_expect_not_found($url, 'sci-fi');

         // make sure we dont get a successful response for the www site
         test_expect_not_found($url, 'www');
         break;
   }

   // basic page content tests
   $browser = new LtkTestFunctional($channel);
   $browser->
      get($url)->

      with('response')->begin()->

         // there should only be one h1
         checkElement('h1', 1)->

         // check for GA
         matches('/google-analytics\.com\/ga\.js.*\"UA-\d+-\d\"/s')->

         // check for ComScore
         matches('/scorecardresearch\.com\/beacon\.js.*COMSCORE\.beacon/s')->

      end()
   ;
}

function backend_login_admin(LtkTestFunctional $browser)
{
   return $browser->
      get('/sfGuardAuth/signin')->
      click('sign in', array('signin' => array(
         'username' => 'ltkadmin',
         'password' => 'ltk123'
      )));
}

function backend_logout(LtkTestFunctional $browser)
{
   return $browser->get('/sfGuardAuth/signout');
}
