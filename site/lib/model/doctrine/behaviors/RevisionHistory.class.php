<?php

class RevisionHistory extends Doctrine_AuditLog
{
   public function getHistory(Doctrine_Record $record)
   {
      $className = $this->_options['className'];

      $query = Doctrine_Core::getTable($className)->createQuery();

      foreach ((array) $this->_options['table']->getIdentifier() as $id)
      {
         $query->andWhere($className . '.' . $id . ' = ?', $record->get($id));
      }

      $query->orderBy($className . '.' . $this->_options['version']['name'] . ' DESC');

      return $query->fetchArray();
   }
}
