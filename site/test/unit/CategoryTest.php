<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';

class CategoryTest extends UnitTestCase
{
   function testFindOneByUrl()
   {
      // check normal usage
      $starTrek = Doctrine::getTable('Category')->findOneByUrl('wiki/Category:Star_Trek', 'sci-fi');
      $this->assertEqual($starTrek->title, 'Star Trek');
      $this->assertEqual($starTrek->Channel->title, 'Sci-Fi');

      // check alternate usage
      $starTrek = Doctrine::getTable('Category')->findOneByUrl(array('url' => 'wiki/Category:Star_Trek', 'channel' => 'sci-fi'));
      $this->assertEqual($starTrek->title, 'Star Trek');
      $this->assertEqual($starTrek->Channel->title, 'Sci-Fi');
   }

   function testFindOneByUrlMissingUrl()
   {
      $this->expectException('Exception');
      Doctrine::getTable('Category')->findOneByUrl();
   }

   function testFindOneByUrlMissingChannelSlug()
   {
      $this->expectException('Exception');
      Doctrine::getTable('Category')->findOneByUrl('wiki/Category:Star_Trek');
   }

   function testFindOneByUrlMissingChannelSlugAlt()
   {
      $this->expectException('Exception');
      Doctrine::getTable('Category')->findOneByUrl(array('url' => 'wiki/Category:Star_Trek'));
   }

   function testFetchTreeByChannel()
   {
      $channel = Doctrine::getTable('Channel')->findOneBySlug('sci-fi');

      // check that the tree doesnt include the root
      $tree = Doctrine::getTable('Category')->fetchTreeByChannel($channel);
      foreach($tree as $category)
      {
         $this->assertFalse($category->getNode()->isRoot());
      }
   }

// TODO: re-enable after publishing is re-implemented
/*
   function testGetPublishedSlideshows()
   {
      $hair = Doctrine::getTable('Category')->findOneByUrl('Category:Hair_Style_Pictures', 'hair');
      foreach($hair->publishedSlideshows as $slideshow)
      {
         $this->assertNotEqual($slideshow->url, 'Goody_Hair_Accessories');
      }
   }
*/
}
