<?php
/**
* Basic batch processing initialization code.
*/

require_once(dirname(__FILE__).'/../../config/ProjectConfiguration.class.php');

ini_set("memory_limit", "1073741824"); //1024M

/**
* Base class for batch processing.
*/
abstract class Batch
{
   protected $name;
   protected $configuration;
   protected $context;
   private $logger;

   public function __construct()
   {
      $this->name = get_class($this);
      $this->configuration = ProjectConfiguration::getApplicationConfiguration('frontend', 'batch', true);
      $this->context = sfContext::createInstance($this->configuration);
      $this->logger = $this->context->getLogger();
   }

   /**
   * Internal function that initiates the primary batch process.
   */
   private function _process()
   {
      $this->logNotice("Starting {$this->name}");
      $this->process();
      $this->logNotice("{$this->name} completed");
   }

   /**
   * Call this to start the batch processing.
   */
   public static function run()
   {
      $class = get_called_class();
      $batch = new $class();
      $batch->_process();
   }

   protected function logInfo($message)
   {
      $this->log($message, sfLogger::INFO);
   }

   protected function logNotice($message)
   {
      $this->log($message, sfLogger::NOTICE);
   }

   protected function logWarning($message)
   {
      $this->log($message, sfLogger::WARNING);
   }

   protected function logError($message)
   {
      $this->log($message, sfLogger::ERR);
   }

   private function log($message, $priority)
   {
      $mem = number_format(round(memory_get_usage() / 1024 / 1024, 2), 2) . 'M';
      $this->logger->log("[$mem] $message", $priority);
   }

   /**
   * Inheriting Batch classes will put their batch processing implementation into this method.
   */
   abstract protected function process();
}
