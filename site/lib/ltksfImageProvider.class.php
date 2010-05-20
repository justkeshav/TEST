<?php
/**
* Defines a programming interface to provide access to image resources and image manipulation functionality.
*
* Also, access is provided to a singleton instance of an image provider of the class type configured in app.yml
*
* To access the singleton instance, use ltksfImageProvider::getInstance()
*/
abstract class ltksfImageProvider extends ltksfFileProvider
{
   // this is meant to be a const but const cant be array
   private static $NAMED_WIDTHS = array('thumb' => 100);

   /**
   * Creates an image on the configured image provider
   *
   * Note: This function will 'consume' the temp file and it will not be available after the function returns.
   *
   * @param string $tempFilename the filename of a temp file containing the image to create
   * @param Image $image the Image object that will hold details of the created image
   *
   * @return boolean true if image creation was successful; otherwise false
   */
   public function create($tempFilename, $image)
   {	
      parent::create($tempFilename, $image);

      $sizes = $image->sizes;
      $sizes['orig'] = $this->getDimensions($tempFilename);

      $this->save($tempFilename, $image, 'orig');

      $image->sizes = $sizes;

      return $this->createSize($image, 'thumb');
   }

   /**
   * Gets the dimensions of an image
   *
   * @param string $filename the location of the image to get dimensions for
   *
   * @return array an array with the wditch of the image stored in the 'w' key and the height in 'h'
   */
   public function getDimensions($filename)
   {
      $size = getimagesize($filename);
      return array('w' => $size[0], 'h' => $size[1]);
   }

   /**
   * Creates a new named size for the image.
   *
   * Note: if the specified max width is not less than the width of the original image then this will just duplicate the original
   *
   * @param Image $image an object containing details of the original image
   * @param string $sizeName the name of the new size to create
   * @param int $maxWidth the maximum width for the new size; may be omitted if the specified size name has a default width
   * @param int $maxHeight the maximum height for the new size; if omitted will use a calculated height based on the aspect ratio of the original image
   *
   * @return boolean true if the size was created or is already available; otherwise false
   */
   public function createSize(Image $image, $sizeName, $maxWidth = 0, $maxHeight = 0)
   {
      // if no width is specified, check for a default
      if($maxWidth == 0)
      {
         if(array_key_exists($sizeName, self::$NAMED_WIDTHS))
         {
            $maxWidth = self::$NAMED_WIDTHS[$sizeName];
         }
         else
         {
            return false;
         }
      }

      assert('is_int($maxWidth) && $maxWidth > 0');
      assert('is_int($maxHeight) && $maxHeight >= 0');

      if(array_key_exists($sizeName, $image->sizes))
      {
         if($maxWidth == $image->sizes[$sizeName]['w'] && ($maxHeight == $image->sizes[$sizeName]['h'] || $maxHeight == 0))
         {
            // this size name already exists with the same dimensions so nothing to do
            return true;
         }
      }

      if(!($tempFile = $this->resize($image, $maxWidth, $maxHeight)))
      {
         return false;
      }

      $sizes = $image->sizes;
      $sizes[$sizeName] = $this->getDimensions($tempFile);

      $this->save($tempFile, $image, $sizeName);

      $image->sizes = $sizes;

      return true;
   }

   /**
   * Resizes an image to the specified width and maintains the original's aspect ratio
   *
   * Note: if the specified width is not less than the width of the original image then this will just duplicate the original
   *
   * @param Image $image an object containing details of the original image
   * @param int $maxWidth the width requested for the new image
   * @param int $maxHeight the height requested for the new image; if omitted will use a calculated height based on the aspect ratio of the original image
   *
   * @return mixed if successful, the name of a temp file containing the resized image; otherwise false
   */
   protected function resize(Image $image, $maxWidth, $maxHeight = 0)
   {
      assert('is_int($maxWidth) && $maxWidth > 0');
      assert('is_int($maxHeight) && $maxHeight >= 0');

      $original = $this->createImageResource($image);

      $newWidth = $width = imagesx($original);
      $newHeight = $height = imagesy($original);

      $ratio = (float)$height / (float)$width;

      if($maxHeight > 0)
      {
         $widthDiff = (float)$width / (float)$maxWidth;
         $heightDiff = (float)$height / (float)$maxHeight;

         if($widthDiff > $heightDiff)
         {
            if($newWidth > $maxWidth)
            {
               $newWidth = $maxWidth;
            }

            $newHeight = round((float)$newWidth * $ratio);
         }
         else
         {
            if($newHeight > $maxHeight)
            {
               $newHeight = $maxHeight;
            }

            $newWidth = round((float)$newHeight / $ratio);
         }
      }
      else
      {
         $newWidth = $maxWidth;
         $newHeight = round((float)$newWidth * $ratio);
      }

      if($newWidth == $width && ($newHeight == $height || $maxHeight == 0))
      {
         $newImage = $original;
      }
      else
      {
         $newImage = imagecreatetruecolor($newWidth, $newHeight);
         imagecopyresampled($newImage, $original, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
         imagedestroy($original);
      }

      $filename = $this->saveImageResource($image, $newImage);

      imagedestroy($newImage);

      return $filename;
   }

   /**
   * Creates an image resource that contains the contents of the original image
   *
   * @param Image $image the image object to create an image resource for
   * @return resource an image resource representing the image
   */
   protected function createImageResource(Image $image)
   {
      $originalFilename = $this->genInternalUri($image);
      $pathinfo = pathinfo($originalFilename);

      switch(strtolower($pathinfo['extension']))
      {
         case 'jpg':
         case 'jpeg':
            assert('imagetypes() & IMG_JPG');
            return imagecreatefromjpeg($originalFilename);

         case 'gif':
            assert('imagetypes() & IMG_GIF');
            return imagecreatefromgif($originalFilename);

         case 'png':
            assert('imagetypes() & IMG_PNG');
            return imagecreatefrompng($originalFilename);

         default:
            throw new UnexpectedValueException("File '$originalFilename' is not a supported image type.");
      }
   }

   /**
   * Saves the specified image resource to a temp file
   *
   * @param Image $image object with details of the original image
   * @param resource $imageResource the image resource to save to a file
   *
   * @return mixed if successful, the filename of a temp file the contains the saved image resource; otherwise false
   */
   protected function saveImageResource(Image $image, $imageResource)
   {
      $success = false;
      $tempFilename = tempnam(sys_get_temp_dir(), 'ltk-image-');

      $originalFilename = $this->genInternalUri($image);
      $pathinfo = pathinfo($originalFilename);

      switch(strtolower($pathinfo['extension']))
      {
         case 'jpg':
         case 'jpeg':
            assert('imagetypes() & IMG_JPG');
            $success = imagejpeg($imageResource, $tempFilename);
            break;

         case 'gif':
            assert('imagetypes() & IMG_GIF');
            $success = imagegif($imageResource, $tempFilename);
            break;

         case 'png':
            assert('imagetypes() & IMG_PNG');
            $success = imagepng($imageResource, $tempFilename);
            break;

         default:
            throw new UnexpectedValueException("File '$originalFilename' is not a supported image type.");
      }

      if($success)
      {
         return $tempFilename;
      }

      return false;
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
      // stub required to extend parameter set	
   }

   /**
   * Internal function to save an image file to a specific image provider.
   *
   * Note: This function should 'consume' (delete, destroy, etc.) the temp file so it will not be available after the function returns.
   *
   * @param string $tempFilename the filename of a temp file containing the image to save
   * @param Image $image the Image object that has details of the image to save
   * @param string $sizeName the name of the size of the image that is being saved
   *
   * @throws Exception if unable to successfully save the file
   */
   protected function save($tempFilename, $image, $sizeName = 'orig')
   {
      // stub required to extend parameter set
   }

   public static function getInstance($resourceType = null)
   {
      return parent::getInstance('image');
   }
}
