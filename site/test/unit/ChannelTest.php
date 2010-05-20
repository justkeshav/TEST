<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';

class ChannelTest extends UnitTestCase
{
   function testSettings()
   {
      $channel = new Channel();
      $channel->setSetting('my-setting', 'its value');
      $this->assertEqual($channel->getSetting('my-setting'), 'its value');
   }

   function testCantSetSettingDirectly()
   {
      $channel = new Channel();
      $this->expectException();
      $channel->settings['my-setting'] = 'its value';
   }
}
