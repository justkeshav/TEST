<?php

class Publishable extends Doctrine_Template
{
   /**
   * Array of Publishable options
   *
   * @var string
   */
   protected $_options = array(
      'name'          =>  'published_at',
      'alias'         =>  null,
      'type'          =>  'timestamp',
      'format'        =>  'Y-m-d H:i:s',
      'expression'    =>  false,
      'options'       =>  array('default' => '9999-12-31 23:59:59')
   );

   /**
   * Set table definition for Publishable behavior
   *
   * @return void
   */
   public function setTableDefinition()
   {
      $name = $this->_options['name'];

      if($this->_options['alias'])
      {
         $name .= ' as ' . $this->_options['alias'];
      }

      $this->hasColumn($name, $this->_options['type'], null, $this->_options['options']);
   }

   /**
   * Add a getPublished() method to any of the models who act as Publishable behavior
   *
   * @return bool true if the model instance is published; otherwise false
   */
   public function isPublished()
   {
      return $this->_invoker->published_at <= date('Y-m-d H:i:s', time());
   }

   /**
   * Add a publish() method to any of the models who act as Publishable behavior
   *
   * @param Doctrine_Connection $conn
   * @return mixed the model instance that invoked this call
   */
   public function publish($conn = null)
   {
      $name = $this->_table->getFieldName($this->_options['name']);

      $this->_invoker->$name = $this->getTimestamp($conn);

      $this->_invoker->save($conn);

      return $this->_invoker;
   }

   /**
   * Gets the timestamp in the correct format based on the way the behavior is configured
   *
   * @return void
   */
   public function getTimestamp($conn = null)
   {
      if($this->_options['expression'] !== false && is_string($this->_options['expression']))
      {
         return new Doctrine_Expression($this->_options['expression'], $conn);
      }
      else
      {
         if($this->_options['type'] == 'date')
         {
            return date($this->_options['format'], time());
         }
         else if($this->_options['type'] == 'timestamp')
         {
            return date($this->_options['format'], time());
         }
         else
         {
            return time();
         }
      }
   }
}
