<?php

class BackendSlideForm extends autoSlideForm
{
   public function configure()
   {
      unset($this['rank']);

      $this->widgetSchema['image_id'] = new ltksfWidgetFormImage(array(
         'image' => $this->getObject()->Image,
         'channel_id' => $this->getObject()->Slideshow->channel_id));
   }

   protected function doSave($con = null)
   {
      parent::doSave($con);

      $slide = $this->getObject();

      if($slide->image_id != $slide->Image->id)
      {
         $image = Doctrine::getTable('Image')->find($slide->image_id);
         if(!$image->hasSize('slide'))
         {
            if(ltksfImageProvider::getInstance()->createSize($image, 'slide', 600, 500))
            {
               $image->save($con);
            }
            else
            {
               throw new RuntimeException("There was a problem saving the slide image.");
            }
         }
      }
   }

   public function updateObject($values = null)
   {
      $slide = parent::updateObject($values);

      if($slide->isNew())
      {
         $slide->rank = count($slide->Slideshow->Slides);
      }

      return $slide;
   }
}
