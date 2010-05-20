<?php

function load_test_data()
{
   $models = array('Title', 'Category', 'TitleCategory', 'Article', 'Slideshow', 'Slide', 'Quiz', 'QuizContent');

   // turn off DQL callbacks and foreign key checks while purging the DB
   Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, false);
   foreach($models as $model)
   {
      Doctrine::getTable($model)->getRecordListener()->setOption('disabled', true);
   }
   Doctrine_Manager::connection()->exec('set foreign_key_checks=0');

   // load the test data (purges all data from the DB first)
   Doctrine_Core::loadData(sfConfig::get('sf_test_dir').'/fixtures');

   // turn stuff back on that was off for purging the DB
   Doctrine_Manager::connection()->exec('set foreign_key_checks=1');
   foreach($models as $model)
   {
      Doctrine::getTable($model)->getRecordListener()->setOption('disabled', false);
   }
   Doctrine_Manager::getInstance()->setAttribute(Doctrine_Core::ATTR_USE_DQL_CALLBACKS, true);
}
