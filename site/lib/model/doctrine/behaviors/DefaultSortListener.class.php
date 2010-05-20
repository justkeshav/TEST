<?php

class DefaultSortListener extends Doctrine_Record_Listener
{
   public function __construct($options)
   {
      $this->setOption($options);
   }

   public function preDqlSelect(Doctrine_Event $event)
   {
      $query = $event->getQuery();
      $orderby = $query->getDqlPart('orderby');

      // only apply the default sort if no other sort has been set
      if(count($orderby) == 0)
      {
         $params = $event->getParams();
         $columnName = $this->_options['column'];
         $sorting = strtoupper($this->_options['sorting']);
         $query->orderBy("{$params['alias']}.$columnName $sorting");
      }
   }
}
