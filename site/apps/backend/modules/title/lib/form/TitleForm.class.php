<?php

class BackendTitleForm extends autoTitleForm
{
   public function configure()
   {
      $this->user = sfContext::getInstance()->getUser()->getGuardUser();
      
      unset($this['status']);
      
      if($this->isNew)
      {
         unset($this['url']);
         unset($this['slug']);
         
         // get default channel that was set when 'save and add' was clicked
         $defaultChannelId = $this->getOption('default_channel_id');
         if ($defaultChannelId)
         {
            $this->getObject()->channel_id = $defaultChannelId;
         }

         // if user = Group Editor, restrict channel dropdown to user's associated channels
         if ($this->user->isGroupEditor())
         {
            $channels = $this->user->getAssociatedChannels(sfGuardUser::ROLE_EDITOR);
            if (sizeof($channels) == 0)
            {
               $this->widgetSchema['channel_id']->setAttribute('disabled','disable');
            }
            else
            {
               $query = Doctrine::getTable("Channel")->createQuery()->andWhereIn('id', $channels);
               $this->widgetSchema['channel_id']->setOption('query', $query); 
            }
         }
      }
      else
      {
         $this->validatorSchema['url']->setOption('required', false);

         unset($this['type']);
         $this->widgetSchema['type'] = new ltksfWidgetFormStaticPartial(array('templateName' => 'type', 'vars' => array('title' => $this->getObject())));
         
         $this->widgetSchema['image_id'] = new ltksfWidgetFormImage(array(
            'image' => $this->getObject()->Image,
            'channel_id' => $this->getObject()->channel_id
         ));

         // make url/channel fields static if Title has been previously published
         if($this->getObject()->hasBeenPublished())
         {
		    $this->widgetSchema['channel_id']->setAttribute('disabled','disable');
		    $this->setValidator('channel_id', new sfValidatorPass());
		    $this->widgetSchema['url']->setAttribute('disabled','disable');
		    $this->setValidator('url', new sfValidatorPass());	
	     }         
      }

      $this->setDefault('type', 'Article');

      $this->widgetSchema['categories_list']->setOption('renderer_class', 'sfWidgetFormSelectDoubleList');
      
      if(!is_null($this->object->channel_id))
      {
         // limit category list to the specified channel
         $query = Doctrine::getTable($this->widgetSchema['categories_list']->getOption('model'))->createQuery();
         $query->addWhere('channel_id = ?', $this->getObject()->channel_id);
         $this->widgetSchema['categories_list']->setOption('query', $query);
      }
      else
      {
         // don;t show any categories until user selects channel Id in form (ajax call made to re-populate is done in form)
         $query = Doctrine::getTable($this->widgetSchema['categories_list']->getOption('model'))->createQuery();
         $query->addWhere('channel_id = ?', -1);
         $this->widgetSchema['categories_list']->setOption('query', $query);
      }  
      
      // only group editors can pre-assign the author
      if (!$this->user->isGroupEditor())
      {
         unset($this['author_user_id']);
      }
      
      // if this form is being accessed by a writer then image only access is permitted
      if ($this->user->isWriter())
      {
         unset($this['title']);
         unset($this['slug']);         
         unset($this['url']);
         unset($this['channel_id']);
         unset($this['author_user_id']);         
         unset($this['categories_list']);         
      }
   }

   public function updateObject($values = null)
   {
	  if($this->getObject()->hasBeenPublished())
      {
         unset($this->values['channel_id']);
         unset($this->values['url']);
      }

      return parent::updateObject($values);
   }

   /**
   * Updates and saves the current object.
   *
   * If you want to add some logic before saving or save other associated
   * objects, this is the method to override.
   *
   * @param mixed $con An optional connection object
   */
   protected function doSave($con = null)
   {
      parent::doSave($con);

      if(empty($this->getObject()->url))
      {
         $this->getObject()->url = "{$this->getObject()->full_slug}";
      }

      $this->getObject()->save($con);   

      $title = $this->getObject();
            
      if($title->type == 'Article' && $title->image_id)
      {
         $image = Doctrine::getTable('Image')->find($title->image_id);
         if(!$image->hasSize('article'))
         {
            $width = $title->image_width ? $title->image_width : 300;
            
            if(ltksfImageProvider::getInstance()->createSize($image, 'article', $width))
            {
               $image->save($con);
            }
            else
            {
               throw new RuntimeException("There was a problem saving the article image.");
            }
         }
      }      
   }
}
