<?php

require_once(dirname(__FILE__).'/init.php');

/**
* Base class for batch processing that needs a database connection.
*/
abstract class DoctrineBatch extends Batch
{
   protected $databaseManager;

   public function __construct()
   {
      parent::__construct();
      $this->databaseManager = new sfDatabaseManager($this->configuration);
      $this->databaseManager->loadConfiguration();
   }
}
