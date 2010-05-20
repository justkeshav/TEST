<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';

class ltksfImageProviderTest extends UnitTestCase
{
   protected $provider;

   function __construct()
   {
      $this->provider = ltksfImageProvider::getInstance();
   }

   function testAmazonCloudFront()
   {
      if($this->provider instanceof ltksfAmazonCloudFrontImageProvider)
      {
         $image = new Image();
         $image->channel_id = 29;

         $tempFile = '/tmp/' . str_replace('/', '-', "iStock_000007065595XSmall[1].jpg");

         $this->assertTrue(copy("/shared/lovetoknow/wwwvhost/channels/skins/Slide/Upload/181/iStock_000007065595XSmall[1].jpg", $tempFile), 'failed to copy to tmp file');
         $this->assertTrue(ltksfImageProvider::getInstance()->create($tempFile, $image), 'failed to create orig image');
         $this->assertTrue(ltksfImageProvider::getInstance()->createSize($image, 'slide', 600, 500), 'failed to create slide sized image');
      }
   }
}
