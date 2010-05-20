<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';

class SlideshowTest extends UnitTestCase
{
// TODO: re-enable after publishing is re-implemented
/*
   function testPublish()
   {
      $slideshow = new Slideshow();
      $slideshow->Channel = Doctrine::getTable('Channel')->findOneBySlug('sci-fi');
      $this->assertFalse($slideshow->isPublished());
      $slideshow->publish();
      $this->assertTrue($slideshow->isPublished());
   }

   function testIsPublished()
   {
      $slideshow1 = Doctrine::getTable('Slideshow')->findOneByUrl('Goody_Hair_Accessories');
      $this->assertFalse($slideshow1->isPublished());

      $slideshow2 = Doctrine::getTable('Slideshow')->findOneByUrl('wiki/Star_Trek_Cast');
      $this->assertTrue($slideshow2->isPublished());
   }
*/
}
