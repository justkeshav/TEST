<?php

class BackendChannelForm extends ChannelForm
{
   public function configure()
   {
      $this->widgetSchema['content'] = new ltksfWidgetFormTextareaCKEditor();

      // exclude users w/ Titler role, super_admin flag from list
      $query = Doctrine::getTable('sfGuardUser')->createQuery('u')
         ->addFrom('u.sfGuardUserGroup role')->Where('role.group_id != ?', sfGuardUser::ROLE_TITLER)
         ->addWhere('u.is_super_admin = 0');

      $this->widgetSchema['channel_writers'] = new sfWidgetFormDoctrineChoice(array(
         'multiple' => true, 'model' => 'sfGuardUser', 'query' => $query));
      $this->widgetSchema['channel_writers']->setOption('renderer_class', 'sfWidgetFormSelectDoubleList');
      $this->setValidator('channel_writers', new sfValidatorPass());

      // handle settings array with individual fields
      unset($this['settings']);
      $this->widgetSchema['settings_analytics_code'] = new sfWidgetFormInputText();
      $this->validatorSchema['settings_analytics_code'] = new sfValidatorString();
      $this->widgetSchema['settings_ad_1'] = new sfWidgetFormInputText();
      $this->validatorSchema['settings_ad_1'] = new sfValidatorString();
      $this->widgetSchema['settings_ad_2'] = new sfWidgetFormInputText();
      $this->validatorSchema['settings_ad_2'] = new sfValidatorString();
      $this->widgetSchema['settings_ad_3'] = new sfWidgetFormInputText();
      $this->validatorSchema['settings_ad_3'] = new sfValidatorString();
      $this->widgetSchema['settings_ad_sidebar'] = new sfWidgetFormInputText();
      $this->validatorSchema['settings_ad_sidebar'] = new sfValidatorString();
      $this->widgetSchema['settings_ad_article_size'] = new sfWidgetFormChoice(array('choices' => array('468x60' => '468x60', '300x250' => '300x250')));
      $this->validatorSchema['settings_ad_article_size'] = new sfValidatorChoice(array('choices' => array('468x60', '300x250')));
      $this->widgetSchema['settings_ad_article_type'] = new sfWidgetFormChoice(array('choices' => array('text_image' => 'Any', 'image' => 'Image/Flash', 'text' => 'Text')));
      $this->validatorSchema['settings_ad_article_type'] = new sfValidatorChoice(array('choices' => array('text_image', 'image', 'text')));
      $this->widgetSchema['settings_ad_article_1'] = new sfWidgetFormInputText();
      $this->validatorSchema['settings_ad_article_1'] = new sfValidatorString();
      $this->widgetSchema['settings_ad_article_2'] = new sfWidgetFormInputText();
      $this->validatorSchema['settings_ad_article_2'] = new sfValidatorString();
      $this->widgetSchema['settings_ad_chitika'] = new sfWidgetFormInputText();
      $this->validatorSchema['settings_ad_chitika'] = new sfValidatorString(array('required' => false));
      $this->widgetSchema['settings_ad_slide'] = new sfWidgetFormInputText();
      $this->validatorSchema['settings_ad_slide'] = new sfValidatorString();
      $this->widgetSchema['settings_footer_text'] = new sfWidgetFormInputText();
      $this->validatorSchema['settings_footer_text'] = new sfValidatorString();

      if (!$this->isNew())
      {
         $this->setDefault('channel_writers', $this->getAssociatedUsers());

         $this->setDefault('settings_analytics_code', $this->getObject()->getSetting('analytics_code'));
         $this->setDefault('settings_ad_1', $this->getObject()->getSetting('ad_1'));
         $this->setDefault('settings_ad_2', $this->getObject()->getSetting('ad_2'));
         $this->setDefault('settings_ad_3', $this->getObject()->getSetting('ad_3'));
         $this->setDefault('settings_ad_sidebar', $this->getObject()->getSetting('ad_sidebar'));
         $this->setDefault('settings_ad_article_size', $this->getObject()->getSetting('ad_article_size'));
         $this->setDefault('settings_ad_article_type', $this->getObject()->getSetting('ad_article_type'));
         $this->setDefault('settings_ad_article_1', $this->getObject()->getSetting('ad_article_1'));
         $this->setDefault('settings_ad_article_2', $this->getObject()->getSetting('ad_article_2'));
         $this->setDefault('settings_ad_chitika', $this->getObject()->getSetting('ad_chitika'));
         $this->setDefault('settings_ad_slide', $this->getObject()->getSetting('ad_slide'));
         $this->setDefault('settings_footer_text', $this->getObject()->getSetting('footer_text'));
      }
   }

   /**
   * Updates the values of the object with the cleaned up values.
   *
   * @param  array $values An array of values
   *
   * @return mixed The current updated object
   */
   public function updateObject($values = null)
   {
      $channel = parent::updateObject($values);

      if(null === $values)
      {
         $values = $this->values;
      }

      $channel->setSetting('analytics_code', $values['settings_analytics_code']);
      $channel->setSetting('ad_1', $values['settings_ad_1']);
      $channel->setSetting('ad_2', $values['settings_ad_2']);
      $channel->setSetting('ad_3', $values['settings_ad_3']);
      $channel->setSetting('ad_sidebar', $values['settings_ad_sidebar']);
      $channel->setSetting('ad_article_size', $values['settings_ad_article_size']);
      $channel->setSetting('ad_article_type', $values['settings_ad_article_type']);
      $channel->setSetting('ad_article_1', $values['settings_ad_article_1']);
      $channel->setSetting('ad_article_2', $values['settings_ad_article_2']);
      $channel->setSetting('ad_chitika', $values['settings_ad_chitika']);
      $channel->setSetting('ad_slide', $values['settings_ad_slide']);
      $channel->setSetting('footer_text', $values['settings_footer_text']);
   }

   protected function doSave($con = null)
   {
      parent::doSave($con);

      // save user/channel associations

      $existing = $this->getAssociatedUsers();

      $values = $this->getValue('channel_writers');
      if (!is_array($values))
      {
         $values = array();
      }

      $unlink = array_diff($existing, $values);
      if (count($unlink))
      {
         foreach ($unlink as $user)
         {
            Doctrine::getTable("UserChannel")->createQuery('c')
               ->where('c.user_id = ?', $user)
               ->addWhere('c.role_id = ?', sfGuardUser::ROLE_WRITER)
               ->addWhere('c.channel_id = ?', $this->getObject()->id)
               ->delete()->execute();
         }
      }

      $link = array_diff($values, $existing);
      if (count($link))
      {
         foreach ($link as $user)
         {
            $userChannel = new UserChannel();
            $userChannel->user_id = $user;
            $userChannel->role_id = sfGuardUser::ROLE_WRITER;
            $userChannel->channel_id = $this->getObject()->id;
            $userChannel->save();
         }
      }
   }

   private function getAssociatedUsers()
   {
      $users = array();
      $results = Doctrine::getTable("UserChannel")->createQuery('c')->where('c.channel_id = ?', $this->getObject()->id)->
                    addWhere('c.role_id = ?', sfGuardUser::ROLE_WRITER)->execute();

      foreach ($results as $userChannel)
      {
         $users[] = $userChannel->user_id;
      }

      return $users;
   }
}
