<?php

/**
 * category module configuration.
 *
 * @package    LoveToKnow
 * @subpackage category
 * @author     Your name here
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class categoryGeneratorConfiguration extends BaseCategoryGeneratorConfiguration
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
