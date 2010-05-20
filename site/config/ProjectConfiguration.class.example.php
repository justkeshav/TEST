<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
   public function setup()
   {
      $this->enablePlugins(
         'sfDoctrinePlugin',
         'ltksfSimpleTestPlugin',
         'sfFormExtraPlugin',
         'ltksfFormExtraPlugin',
         'sfDoctrineGuardPlugin');
  }

   public function configureDoctrine(Doctrine_Manager $manager)
   {
      $manager->setAttribute(Doctrine_Core::ATTR_USE_NATIVE_ENUM, true);

      // required for some behaviors
      $manager->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);

      // HACK: it would be better to use code like sfConfig::get('app_query_cache_provider_class') to load these
      //       configuration settings rather than making this an example file, but at the point this method
      //       is called, app.yml has not been loaded into sfConfig and I couldnt find a way to make these settings
      //       in a project-wide manner by any other means, so until that is possble, this will have to be an
      //       example file

      // HACK: should be: $queryCacheClass = sfConfig::get('app_query_cache_provider_class');
      $queryCacheClass = '${provider.cache.query}';
      assert('class_exists($queryCacheClass)');
      $manager->setAttribute(Doctrine_Core::ATTR_QUERY_CACHE, new $queryCacheClass());

      // HACK: should be: $resultCacheClass = sfConfig::get('app_result_cache_provider_class');
      $resultCacheClass = '${provider.cache.result}';
      assert('class_exists($resultCacheClass)');
      $manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE, new $resultCacheClass());

      // HACK: should be: $manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE_LIFESPAN, intval(sfConfig::get('app_result_cache_provider_lifespan')));
      $manager->setAttribute(Doctrine_Core::ATTR_RESULT_CACHE_LIFESPAN, intval('${provider.cache.result.lifespan}'));
   }
}
