<?php

class BackendImageForm extends autoImageForm
{
   public function configure()
   {
      if($this->isNew)
      {
         $this->widgetSchema['upload'] = new ltksfWidgetFormUploadify(array('file_types' => array('jpg', 'jpeg', 'gif', 'png'), 'file_desc' => 'Image Files'));
         $this->validatorSchema['upload'] = new ltksfValidatorUploadify(array('file_types' => array('jpg', 'jpeg', 'gif', 'png')));
      }
      else
      {
         $this->widgetSchema['thumb'] = new ltksfWidgetFormStaticPartial(array('templateName' => 'image/thumb', 'vars' => array('image' => $this->getObject())));
         $this->widgetSchema['links'] = new ltksfWidgetFormStaticPartial(array('templateName' => 'image/links', 'vars' => array('image' => $this->getObject())));
      }
   }

   public function updateObject($values = null)
   {
      $object = parent::updateObject($values);

      if($object->isNew())
      {
         if(null === $values)
         {
            $values = $this->values;
         }

         $tempImageFile = sfConfig::get('sf_web_dir') . $values['upload'];

         if(!ltksfImageProvider::getInstance()->create($tempImageFile, $object))
         {
            throw new RuntimeException("There was a problem saving the image file '$tempImageFile'.");
         }
      }

      return $object;
   }
}
