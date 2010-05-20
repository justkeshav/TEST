<?php

require_once dirname(__FILE__).'/../bootstrap/Doctrine.php';

class UserTest extends UnitTestCase
{
   function testUserRoleAccessors()
   {
	  $user = Doctrine::getTable('sfGuardUser')->findOneById('3');
	  $this->assertEqual($user->isEditor(), false);
	  $this->assertEqual($user->isWriter(), true);
	  $this->assertEqual($user->getRole(), sfGuardUser::ROLE_WRITER);
	
	  $user = Doctrine::getTable('sfGuardUser')->findOneById('1');
	  $this->assertEqual($user->isAdmin(), true);	
   }

   function testUserLevelAccessors()
   {
	  $user = Doctrine::getTable('sfGuardUser')->findOneById('2');
	
      // Editors are premium writers by default
	  $this->assertEqual($user->isPremiumWriter(), true);
	  $this->assertEqual($user->getWriterLevel(), sfGuardUser::LEVEL_PREMIUM_WRITER);
	
	  $user = Doctrine::getTable('sfGuardUser')->findOneById('3');
	
	  // Editor level check returns -1 if user != editor
	  $this->assertEqual($user->getEditorLevel(), -1);
	
	  $this->assertEqual($user->getWriterLevel(), sfGuardUser::LEVEL_PREMIUM_WRITER);
	  $this->assertEqual($user->isPremiumWriter(), true);
   }

   function testUserAssociatedChannels()
   {
	  $user = Doctrine::getTable('sfGuardUser')->findOneById('2');
		
      // user is editor for 'diet' channel
	  $channels = $user->getAssociatedChannels(sfGuardUser::ROLE_EDITOR);	
	  $this->assertEqual(sizeof($channels), 1); 
	  $this->assertEqual($channels[0], 2); 
	  
	  // user has writer access on 3 channels
	  $channels = $user->getAssociatedChannels(sfGuardUser::ROLE_WRITER);	
	  $this->assertEqual(sizeof($channels), 3);
	
	  // user has writer access on Yoga channel
	  $this->assertEqual($user->isAssociatedChannel(sfGuardUser::ROLE_WRITER, 1), true);
	
	  // admin user has access to all channels
	  $user = Doctrine::getTable('sfGuardUser')->findOneById('1');
	  $this->assertEqual($user->isAssociatedChannel(sfGuardUser::ROLE_WRITER, 22), true);		
   }
}
