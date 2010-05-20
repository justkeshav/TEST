<?php

/**
 * channel module helper.
 *
 * @package    LoveToKnow
 * @subpackage channel
 * @author     Your name here
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class channelGeneratorHelper extends BaseChannelGeneratorHelper
{
   public function linkToEdit($channel, $params)
   {
	  $user = sfContext::getInstance()->getUser()->getGuardUser();
      if ($user->isAdmin() || $user->isAssociatedChannel(sfGuardUser::ROLE_EDITOR, $channel->id))
      {
	     return parent::linkToEdit($channel, $params);
      }
   }
}
