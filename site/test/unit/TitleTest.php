<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';

class TitleTest extends UnitTestCase
{
   function testGetFullSlug()
   {
      $title = new Title();
      $title->slug = 'my-slug';

      // test with no categories
      $this->assertEqual($title->full_slug, 'my-slug');

      $cat = new Category();
      $cat->slug = 'cat-slug';
      $title->Categories[] = $cat;

      // test with category
      $this->assertEqual($title->full_slug, 'cat-slug/my-slug');
   }
}
