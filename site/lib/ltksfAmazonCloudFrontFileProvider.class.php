<?php
/**
* Implements a fileprovider using Amazon's CloudFront CDN.
*/

require_once(dirname(__FILE__).'/vendor/s3-php5-curl/S3.php');

class ltksfAmazonCloudFrontFileProvider extends ltksfFileProvider
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
      return sfConfig::get('app_file_provider_host');
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
      return "{$file->Channel->slug}/files";
   }

   /**
   * Generates a uri for the specified file hat will only be used internally on the local server.
   *
   * @param File $file the file to generate the uri for
   *
   * @return string a uri for the file that is optimized for internal/local usage
   */
   protected function genInternalUri($file)
   {
      return 'http://' . $this->genHost($file) . '/' . $this->genPath($file) . "{$file->id}-{$file->filename}";
   }

   /**
   * Saves the file so that it is accessible from the local web server.
   *
   * Note: This function will move the temp file so it will not be available after the function returns.
   *
   * @param string $tempFilename the filename of a temp file
   * @param File $file the file object that has details of the file to save
   *
   * @throws RuntimeException if it cannot create the dir to contain the file or if it cannot move the file
   */
   protected function save($tempFilename, $file)
   {
      $s3 = new S3(sfConfig::get('app_image_provider_access_key'), sfConfig::get('app_image_provider_secret_key'));

      if($s3->putObjectFile($tempFilename, sfConfig::get('app_image_provider_bucket'), $this->genPath($file) . "{$file->id}-{$file->filename}", S3::ACL_PUBLIC_READ))
      {
         unlink($tempFilename);
      }
      else
      {
         throw new RuntimeException("Failed to upload '$tempFilename' to Amazon CloudFront.");
      }
   }
}

