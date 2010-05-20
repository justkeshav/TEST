<?php
/**
* Implements an image provider using the server's local file system.
*/
class ltksfFileSystemImageProvider extends ltksfImageProvider
{
   /**
   * Generates the host name for the server that will host the image.
   *
   * @param Image $image the image that will be hosted
   *
   * @return string the host name for the server that will host the image
   */
   public function genHost($image)
   {
      return "{$image->Channel->slug}." . sfConfig::get('app_host');
   }

   /**
   * Generates the path prefix for the location where the image will be stored.
   *
   * @param Image $image the image that will be hosted
   *
   * @return string the path prefix for the location where the image will be stored
   */
   public function genPath($image)
   {
      return 'images';
   }

   /**
   * Generates a uri for the specified image size that will only be used internally on the local server.
   *
   * @param Image $image the image to generate the uri for
   * @param string $sizeName the name of the size of the image to generate a uri for
   *
   * @return string a uri for the image that is optimized for internal/local usage
   */
   protected function genInternalUri($image, $sizeName = 'orig')
   {
      return sfConfig::get('sf_web_dir') . "/images/$sizeName/{$image->id}-{$image->filename}";
   }

   /**
   * Saves the iamge file so that it is accessible from the local web server.
   *
   * Note: This function will move the temp file so it will not be available after the function returns.
   *
   * @param string $tempFilename the filename of a temp file containing the image to save
   * @param Image $image the Image object that has details of the image to save
   * @param string $sizeName the name of the size of the image that is being saved
   *
   * @throws RuntimeException if it cannot create the dir to contain the file or if it cannot move the file
   */
   protected function save($tempFilename, $image, $sizeName = 'orig')
   {
      $targetFilename = $this->genInternalUri($image, $sizeName);

      $targetDirname = dirname($targetFilename);

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
