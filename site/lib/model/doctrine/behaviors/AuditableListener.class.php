<?php

class AuditableListener extends Doctrine_Record_Listener
{
   public function __construct($options)
   {
      $this->setOption($options);
   }

   protected function getUser()
   {
      $user = null;

      if(sfContext::hasInstance())
      {
         $user = sfContext::getInstance()->getUser();
         if(isset($user))
         {
            $user = $user->getGuardUser();
         }
      }

      if($user instanceof sfGuardUser)
      {
         return $user->id;
      }

      return 1;// defaulted to ltkadmin to avoid errors in phing - jimd 4/7/10
   }

   protected function auditField(Doctrine_Event $event, $options, $nameKey = 'name')
   {
      $invoker = $event->getInvoker();
      $table = $invoker->getTable();
      $name = $table->getFieldName($options[$nameKey]);
      $userName = $table->getFieldName($options["user_$nameKey"]);

      $modified = $invoker->getModified();
      if(isset($modified[$name]) && !isset($modified[$userName]))
      {
         $invoker->$userName = $this->getUser();
      }
   }

   protected function auditFieldDql(Doctrine_Event $event, $options, $nameKey = 'name')
   {
      $invoker = $event->getInvoker();
      $table = $invoker->getTable();
      $name = $table->getFieldName($options[$nameKey]);
      $userName = $table->getFieldName($options["user_$nameKey"]);

      $params = $event->getParams();
      $field = $params['alias'] . '.' . $name;
      $userField = $params['alias'] . '.' . $userName;

      $query = $event->getQuery();

      if($query->contains($field) && !$query->contains($userField))
      {
         $query->set($userField, '?', $this->getUser());
      }      
   }

   protected function auditTemplateDql(Doctrine_Event $event, $templateName, array $fields = array(), $nameKey = 'name', $disabledKey = 'disabled')
   {
      $this->auditTemplate($event, $templateName, $fields, $nameKey, $disabledKey, true);
   }

   protected function auditTemplate(Doctrine_Event $event, $templateName, array $fields = array(), $nameKey = 'name', $disabledKey = 'disabled', $isDql = false)
   {
      $auditFieldMethod = ($isDql ? 'auditFieldDql' : 'auditField');

      if(empty($fields))
      {
         if(!$this->_options[$templateName][$disabledKey])
         {
            $this->$auditFieldMethod($event, $this->_options[$templateName], $nameKey);
         }
      }
      else
      {
         foreach($fields as $field)
         {
            if(!$this->_options[$templateName][$field][$disabledKey])
            {
               $this->$auditFieldMethod($event, $this->_options[$templateName][$field], $nameKey);
            }
         }
      }
   }

   public function preInsert(Doctrine_Event $event)
   {
      $this->auditTemplate($event, 'Timestampable', array('created', 'updated'));
      $this->auditTemplate($event, 'Publishable');
   }

   public function preUpdate(Doctrine_Event $event)
   {
      $this->auditTemplate($event, 'Timestampable', array('updated'));
      $this->auditTemplate($event, 'Publishable');
   }

   public function preDelete(Doctrine_Event $event)
   {
      $this->auditTemplate($event, 'SoftDelete');
   }

   public function preDqlUpdate(Doctrine_Event $event)
   {
      $this->auditTemplateDql($event, 'Timestampable', array('updated'));
   }

   public function preDqlDelete(Doctrine_Event $event)
   {
      $this->auditTemplateDql($event, 'SoftDelete');
   }
}
