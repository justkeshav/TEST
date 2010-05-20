<?php

class DerivativeFilter extends Doctrine_Record_Filter
{
   protected $_options = array();

   public function __construct($options)
   {
      $this->_options = $options;
   }

   public function init()
   {
      $this->_table->getRelation($this->_options['base']);
   }

   public function filterSet(Doctrine_Record $record, $name, $value)
   {
      $model = $this->_options['base'];
     // try {
      $record->$model->$name = $value;
      return $record;
     // } catch (Exception $e) {}
      throw new Doctrine_Record_UnknownPropertyException(sprintf('Unknown record property / related component "%s" on "%s"', $name, get_class($record)));
   }

   public function filterGet(Doctrine_Record $record, $name)
   {
      $model = $this->_options['base'];
    //  try {
      return $record->$model->$name;
     // } catch (Exception $e) {}
      throw new Doctrine_Record_UnknownPropertyException(sprintf('Unknown record property / related component "%s" on "%s"', $name, get_class($record)));
   }
}
