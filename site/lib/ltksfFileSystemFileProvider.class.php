<?php
/**
* Implements a file provider using the server's local file system.
*/
class ltksfFileSystemFileProvider extends ltksfFileProvider
{	
   /**
   * Creates a file on the configured file provider
   *
   * Note: This function will 'consume' the temp file and it will not be available after the function returns.
   *
   * @param string $tempFilename the filename of a temp file to create
   */
   public function create($tempFilename, $file)
   {	
      parent::create($tempFilename, $file);
      $this->save($tempFilename, $file);

      return;
   }
	
   /**
   * Generates the host name for the server that will host the file.
   *
   * @param File $file the file that will be hosted
   *
   * @return string the host name for the server that will host the file
   */
   public function genHost($file)
   {
      return "{$file->Channel->slug}." . sfConfig::get('app_host');
   }

   /**
   * Generates the path prefix for the location where the file will be stored.
   *
   * @param File $file the image that will be hosted
   *
   * @return string the path prefix for the location where the file will be stored
   */
   public function genPath($file)
   {
      return 'files';
   }

   /**
   * Generates a uri for the specified file that will only be used internally on the local server.
   *
   * @param File $file the file to generate the uri for
   *
   * @return string a uri for the file that is optimized for internal/local usage
   */
   protected function genInternalUri($file)
   {
      return sfConfig::get('sf_web_dir') . "/files/{$file->id}-{$file->filename}";
   }

   /**
   * Saves the file so that it is accessible from the local web server.
   *
   * Note: This function will move the temp file so it will not be available after the function returns.
   *
   * @param string $tempFilename the filename of a temp file to save
   * @param File $file the File object that has details of the file to save
   *
   * @throws RuntimeException if it cannot create the dir to contain the file or if it cannot move the file
   */
   protected function save($tempFilename, $file)
   {
      $targetFilename = $this->genInternalUri($file);
      $targetDirname = dirname($tempFilename);

      if(!file_exists($targetDirname))
      {
         if(mkdir($targetDirname, 0775, true))
         {
            if(chmod($targetDirname, 0775))
            {
               if(!chgrp($targetDirname, sfConfig::get('app_web_server_group')))
               {
                  throw new RuntimeException("Failed to change group for '$targetDirname'.");
               }
            }
            else
            {
               throw new RuntimeException("Failed to change permissions of '$targetDirname'.");
            }
         }
         else
         {
            throw new RuntimeException("Failed to create directory '$targetDirname'.");
         }
      }

      if(rename($tempFilename, $targetFilename))
      {
         if(chmod($targetFilename, 0775))
         {
            if(!chgrp($targetFilename, sfConfig::get('app_web_server_group')))
            {
               throw new RuntimeException("Failed to change group for '$targetFilename'.");
            }
         }
         else
         {
            throw new RuntimeException("Failed to change permissions of '$targetFilename'.");
         }
      }
      else
      {
         throw new RuntimeException("Failed to rename '$tempFilename' to '$targetFilename'.");
      }
   }
}
