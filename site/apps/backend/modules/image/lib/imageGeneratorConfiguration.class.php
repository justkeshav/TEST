<?php

/**
 * image module configuration.
 *
 * @package    LoveToKnow
 * @subpackage image
 * @author     Your name here
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class imageGeneratorConfiguration extends BaseImageGeneratorConfiguration
{
   public function getChannel()
   {
      return $this->channel;
   }

   public function setChannel(Channel $channel)
   {
      $this->channel = $channel;
   }

   public function getFormOptions()
   {
      return array_merge(parent::getFormOptions(), array('channel' => isset($this->channel) ? $this->channel : null));
   }
}
