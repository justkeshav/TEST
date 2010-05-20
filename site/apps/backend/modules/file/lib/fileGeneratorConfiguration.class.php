<?php

/**
 * file module configuration.
 *
 * @package    LoveToKnow
 * @subpackage file
 * @author     Your name here
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class fileGeneratorConfiguration extends BaseFileGeneratorConfiguration
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
