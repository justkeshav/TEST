<?php
/**
* Defines a programming interface to provide access to file resources.
*
* Also, access is provided to a singleton instance of a file provider of the class type configured in app.yml
*
* To access the singleton instance, use ltksfFileProvider::getInstance()
*/
abstract class ltksfFileProvider
{
   /**
   * Creates a file on the configured file provider
   *
   * Note: This function will 'consume' the temp file and it will not be available after the function returns.
   *
   * @param string $tempFilename the filename of a temp file to create
   */
   public function create($tempFilename, $resource)
   {	
      $resource->host = $this->genHost($resource);
      $resource->path = $this->genPath($resource);
      $resource->filename = str_replace(' ', '-', basename($tempFilename));

      if ($resource->isNew())
      {
         $resource->save();
      }

      return;
   }

   /**
   * Generates the host name for the server that will host the file.
   *
   * @param Object $resource the file resource that will be hosted
   *
   * @return string the host name for the server that will host the file
   */
   public abstract function genHost($resource);

   /**
   * Generates the path prefix for the location where the file will be stored.
   *
   * @param Object $resource the file that will be hosted
   *
   * @return string the path prefix for the location where the file will be stored
   */
   public abstract function genPath($resource);

   /**
   * Generates a uri for the specified image size that will only be used internally on the local server.
   *
   * @param Object $resource the image to generate the uri for
   *
   * @return string a uri for the file that is optimized for internal/local usage
   */
   protected abstract function genInternalUri($resource);

   /**
   * Internal function to save a file to a specific file provider.
   *
   * Note: This function should 'consume' (delete, destroy, etc.) the temp file so it will not be available after the function returns.
   *
   * @param string $tempFilename the filename of a temp file to save
   * @param Object $resource the resource object that has details of the file to save
   *
   * @throws Exception if unable to successfully save the file
   */
   protected abstract function save($tempFilename, $resource);

   private static $instance = null;
   public static function getInstance($resourceType = null)
   {
      if(!isset(self::$instance))
      {
         $providerClass = sfConfig::get('app_' . ($resourceType == null ? 'file' : $resourceType) . '_provider_class');
         assert('class_exists($providerClass)');
         self::$instance = new $providerClass();
      }

      return self::$instance;
   }
}
