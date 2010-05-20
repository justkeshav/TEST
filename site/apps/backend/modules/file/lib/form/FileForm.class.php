<?php

class BackendFileForm extends autoFileForm
{
   public function configure()
   {	
      if($this->isNew)
      {
         $this->widgetSchema['upload'] = new ltksfWidgetFormUploadify(array('file_types' => array('pdf'), 'file_desc' => 'Files'));
         $this->validatorSchema['upload'] = new ltksfValidatorUploadify(array('file_types' => array('pdf')));
      }
      else
      {
         $this->widgetSchema['link'] = new ltksfWidgetFormStaticPartial(array('templateName' => 'file/link', 'vars' => array('file' => $this->getObject())));
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

         $tempFile = sfConfig::get('sf_web_dir') . $values['upload'];
         ltksfFileProvider::getInstance()->create($tempFile, $object);
      }

      return $object;
   }
}
