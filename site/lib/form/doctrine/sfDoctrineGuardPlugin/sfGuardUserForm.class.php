<?php

/**
 * sfGuardUser form.
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserForm extends PluginsfGuardUserForm
{
   public function configure()
   {
      parent::configure();
	
      unset(
         $this['last_login'],
         $this['updated_at'],
         $this['created_at'],
         $this['salt'],
         $this['algorithm'],
         $this['sf_guard_user_group_list'],
         $this['sf_guard_user_permission_list']
      );

      $this->widgetSchema['password'] = new sfWidgetFormInputPassword();
      $this->widgetSchema['password2'] = new sfWidgetFormInputPassword();

      // change role & level from multiple select to dropdown
      $this->widgetSchema['groups_list']->setOption('multiple', false);
      $this->widgetSchema['permissions_list']->setOption('multiple',false);

      $this->widgetSchema['editor_channels'] = new sfWidgetFormDoctrineChoice(array(
         'multiple' => true, 'model' => 'Channel'));
      $this->widgetSchema['editor_channels']->setOption('renderer_class', 'sfWidgetFormSelectDoubleList');
      $this->widgetSchema['writer_channels'] = new sfWidgetFormDoctrineChoice(array(
         'multiple' => true, 'model' => 'Channel'));
      $this->widgetSchema['writer_channels']->setOption('renderer_class', 'sfWidgetFormSelectDoubleList');

      if (!$this->isNew())
      {
         $this->widgetSchema['last_login'] = new ltksfWidgetFormStaticDateTime();
         $this->widgetSchema['updated_at'] = new ltksfWidgetFormStaticDateTime();
         $this->widgetSchema['created_at'] = new ltksfWidgetFormStaticDateTime();

         // set defaults for Editor/Writer channel list
         $this->setDefault('editor_channels', $this->getObject()->getAssociatedChannels(sfGuardUser::ROLE_EDITOR));
         $this->setDefault('writer_channels', $this->getObject()->getAssociatedChannels(sfGuardUser::ROLE_WRITER));
      }

      // validators
      $this->setValidator('username', new sfValidatorString(array('required' => true, 'min_length' => 4)));
      $this->setValidator('password2', new sfValidatorPass());
      $this->setValidator('editor_channels', new sfValidatorPass());
      $this->setValidator('writer_channels', new sfValidatorPass());		

      // Group Editors only see Properties section
      if(!sfContext::getInstance()->getUser()->getGuardUser()->isAdmin())
      {
         unset($this['username'],
               $this['password'],
               $this['is_active'],
               $this['is_super_admin']
         );	
         $this->widgetSchema['password2'] = new sfWidgetFormInputHidden();
         $this->widgetSchema['groups_list']->setAttribute('disabled','disable');
	  }
	  else
	  {
		  $this->validatorSchema->setPostValidator(
	         new sfValidatorCallback(array('callback' => array($this, 'checkPassword'))));
	  }
   }

   protected function doSave($con = null)
   {
      if ($this->isNew())
      {
         $this->values['created_at'] = time();
      }
      $this->values['updated_at'] = time();

      if ($this->getValue('groups_list') == null)
      {
	     unset($this['groups_list']);
	  }
	
      $oldRole = $this->getObject()->getRole();
      $newRole = ( $this->getValue('groups_list') == null ? $oldRole : current($this->getValue('groups_list')) );

      if ($newRole == sfGuardUser::ROLE_TITLER)
      {
	     unset($this['permissions_list']);
	  }
	
      parent::doSave($con);

      // handle role demotion
      if ($oldRole == sfGuardUser::ROLE_EDITOR && $newRole != $oldRole)
      {
         $this->values['editor_channels'] = null;
      }
      if ($newRole == sfGuardUser::ROLE_TITLER && $oldRole != $newRole)
      {
         $this->values['writer_channels'] = null;
      }
		
      // update associated channel lists
      $this->updateAssociatedChannels(sfGuardUser::ROLE_EDITOR);
      $this->updateAssociatedChannels(sfGuardUser::ROLE_WRITER);
   }

   public function checkPassword($validator, $values)
   {
      if (!$this->isNew() && strlen($values['password']) == 0)
      {
         // edit action, change password left blank
         return $values;
      }
	
      if (strlen($values['password']) < 8)
      {
         // password must be 8 characters
         $error = new sfValidatorError($validator, 'Password must be at least 8 characters');
         throw new sfValidatorErrorSchema($validator, array('password' => $error));
      }

	  if (!preg_match('/[0-9]+/', $values['password']))
	  {
		// password must contain at least 1 numeral
		$error = new sfValidatorError($validator, 'Password must contain at least 1 numeral');		
		throw new sfValidatorErrorSchema($validator, array('password' => $error));
	  }	
	
      if ($values['password'] != $values['password2'])
      {
         // passwords do not match, throw an error
         $error = new sfValidatorError($validator, 'Password fields do not match');	
         throw new sfValidatorErrorSchema($validator, array('password' => $error));
      }
 
      return $values;
   }

   private function updateAssociatedChannels($role_id)
   {
      $existing = $this->getObject()->getAssociatedChannels($role_id);

      $field = ( $role_id == sfGuardUser::ROLE_EDITOR ? 'editor_channels' : 'writer_channels' );
      $values = $this->getValue($field);
      if (!is_array($values))
      {
         $values = array();
      }

      $unlink = array_diff($existing, $values);
      if (count($unlink))
      {
         foreach ($unlink as $channel)
         {
            Doctrine::getTable("UserChannel")->createQuery('c')
                  ->where('c.user_id = ?', $this->getObject()->id)
                  ->addWhere('c.role_id = ?', $role_id)
                  ->addWhere('c.channel_id = ?', $channel)
                  ->delete()->execute();     
         }
      }

      $link = array_diff($values, $existing);
      if (count($link))
      { 
         foreach ($link as $channel)
         {	
	        $userChannel = new UserChannel();
	        $userChannel->user_id = $this->getObject()->id;
	        $userChannel->role_id = $role_id;
	        $userChannel->channel_id = $channel;
	        $userChannel->save();
	     }
      }
   }
}
