<?php

class BackendChannelDetailsForm extends autoChannelDetailsForm
{
   public function configure()
   {     
          $channel = $this->getObject()->channel_id;
    
      $this->widgetSchema['channel_id']->setAttribute('value',$channel);

      $this->widgetSchema['highlight_content'] = new ltksfWidgetFormTextareaCKEditor();
      $this->widgetSchema['popular1_content'] = new ltksfWidgetFormTextareaCKEditor();
      $this->widgetSchema['popular2_content'] = new ltksfWidgetFormTextareaCKEditor();
      $this->widgetSchema['highlight_title_id'] = new ltksfWidgetFormChannelDetails(array(
         'title' => $this->getObject()->highlight_title_id,
         'channel_id' => $channel));

      $this->widgetSchema['popular1_title_id'] = new ltksfWidgetFormChannelDetails(array(
         'title' => $this->getObject()->popular1_title_id,
         'channel_id' => $channel));

      $this->widgetSchema['popular2_title_id'] = new ltksfWidgetFormChannelDetails(array(
         'title' => $this->getObject()->popular1_title_id,
         'channel_id' => $channel));

      $this->widgetSchema['about_title_id'] = new ltksfWidgetFormChannelDetails(array(
         'title' => $this->getObject()->about_title_id,
         'channel_id' => $channel));      

      if ($this->getObject()->isNew())
      {
        $this->widgetSchema->setDefault('channel_id',$channel);
      }
      else
      {
        $this->widgetSchema['highlight_title_id']->setAttribute('value',$this->getObject()->highlight_title_id);
        $this->widgetSchema['popular1_title_id']->setAttribute('value',$this->getObject()->popular1_title_id);
        $this->widgetSchema['popular2_title_id']->setAttribute('value',$this->getObject()->popular2_title_id);
        $this->widgetSchema['about_title_id']->setAttribute('value',$this->getObject()->about_title_id);
      }
   }

   protected function doSave($con = null)
   {
     parent::doSave($con);

     $Details = $this->getObject();
      
     $image = Doctrine::getTable('Image')->find($Details->Highlight->Image->id);     
     if($image)
     {
         if(!$image->hasSize('highlight'))
         {
            if(! ltksfImageProvider::getInstance()->createSize($image, 'highlight', 120))
            {
               throw new RuntimeException("There was a problem saving the highlight image.");
            }
         }
     }
   }
   
}

