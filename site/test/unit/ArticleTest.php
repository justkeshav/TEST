<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';

class ArticleTest extends UnitTestCase
{
   function testFindOneByUrl()
   {
      // check normal usage
      $starTrek = Doctrine::getTable('Article')->findOneByUrl('wiki/Q', 'sci-fi');
      $this->assertEqual($starTrek->title, 'Q');
      $this->assertEqual($starTrek->Channel->title, 'Sci-Fi');

      // check alternate usage
      $starTrek = Doctrine::getTable('Article')->findOneByUrl(array('url' => 'wiki/Q', 'channel' => 'sci-fi'));
      $this->assertEqual($starTrek->title, 'Q');
      $this->assertEqual($starTrek->Channel->title, 'Sci-Fi');
   }

   function testFindOneByUrlMissingUrl()
   {
      $this->expectException('Exception');
      Doctrine::getTable('Article')->findOneByUrl();
   }

   function testFindOneByUrlMissingChannelSlug()
   {
      $this->expectException('Exception');
      Doctrine::getTable('Article')->findOneByUrl('wiki/Q');
   }

   function testFindOneByUrlMissingChannelSlugAlt()
   {
      $this->expectException('Exception');
      Doctrine::getTable('Article')->findOneByUrl(array('url' => 'wiki/Q'));
   }
}
