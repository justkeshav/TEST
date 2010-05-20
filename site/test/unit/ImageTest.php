<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';

class ImageTest extends UnitTestCase
{
   function testSearchByFilename()
   {
      $scifi = Doctrine::getTable('Channel')->findOneBySlug('sci-fi');
      $images = Doctrine::getTable('Image')->searchByFilename($scifi, 'treksave', 10);
      $this->assertEqual($images->count(), 10);
   }

   function testSearchByFilenameMaxNotExceeded()
   {
      $scifi = Doctrine::getTable('Channel')->findOneBySlug('sci-fi');
      $images = Doctrine::getTable('Image')->searchByFilename($scifi, 'treksave', 5);
      $this->assertEqual($images->count(), 5);
   }

   function testSearchByFilenameWrongChannel()
   {
      $scifi = Doctrine::getTable('Channel')->findOneBySlug('sci-fi');
      $images = Doctrine::getTable('Image')->searchByFilename($scifi, 'goody', 10);
      $this->assertEqual($images->count(), 0);
   }

   function testGenUrl()
   {
      $image = new Image();
      $image->host = 'example.com';
      $image->path = 'path/to/image';
      $image->filename = 'myimage.jpg';
      $this->assertEqual($image->genUrl('mysize'), 'http://example.com/path/to/image/mysize/-myimage.jpg');
   }

   function testGetOrigUrl()
   {
      $image = new Image();
      $image->host = 'example.com';
      $image->path = 'path/to/image';
      $image->filename = 'myimage.jpg';
      $this->assertEqual($image->origUrl, 'http://example.com/path/to/image/orig/-myimage.jpg');
   }

   function testGetThumbUrl()
   {
      $image = new Image();
      $image->host = 'example.com';
      $image->path = 'path/to/image';
      $image->filename = 'myimage.jpg';
      $this->assertEqual($image->thumbUrl, 'http://example.com/path/to/image/thumb/-myimage.jpg');
   }

   function testHasSize()
   {
      $image = Doctrine::getTable('Image')->findOneByFilename('Simon_Pegg.jpg');
      $this->assertTrue($image->hasSize('orig'));
      $this->assertTrue($image->hasSize('thumb'));
      $this->assertTrue($image->hasSize('slide'));
      $this->assertFalse($image->hasSize('josh'));
   }
}
