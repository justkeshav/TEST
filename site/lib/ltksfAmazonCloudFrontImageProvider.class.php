<?php
/**
* Implements an image provider using Amazon's CloudFront CDN.
*/

require_once(dirname(__FILE__).'/vendor/s3-php5-curl/S3.php');

class ltksfAmazonCloudFrontImageProvider extends ltksfImageProvider
{
   /**
   * Generates the host name for the server that will host the image.
   *
   * @param Image $image the image that will be hosted
   *
   * @return string the host name for the server that will host the image
   */
   public function genHost(Image $image)
   {
      return sfConfig::get('app_image_provider_host');
   }

   /**
   * Generates the path prefix for the location where the image will be stored.
   *
   * @param Image $image the image that will be hosted
   *
   * @return string the path prefix for the location where the image will be stored
   */
   public function genPath(Image $image)
   {
      return "{$image->Channel->slug}/images";
   }

   /**
   * Generates a uri for the specified image size that will only be used internally on the local server.
   *
   * @param Image $image the image to generate the uri for
   * @param string $sizeName the name of the size of the image to generate a uri for
   *
   * @return string a uri for the image that is optimized for internal/local usage
   */
   protected function genInternalUri(Image $image, $sizeName = 'orig')
   {
      return 'http://' . $this->genHost($image) . '/' . $this->genPath($image) . "/$sizeName/{$image->id}-{$image->filename}";
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
   protected function save($tempFilename, Image $image, $sizeName = 'orig')
   {
      $s3 = new S3(sfConfig::get('app_image_provider_access_key'), sfConfig::get('app_image_provider_secret_key'));

      if($s3->putObjectFile($tempFilename, sfConfig::get('app_image_provider_bucket'), $this->genPath($image) . "/$sizeName/{$image->id}-{$image->filename}", S3::ACL_PUBLIC_READ))
      {
         unlink($tempFilename);
      }
      else
      {
         throw new RuntimeException("Failed to upload '$tempFilename' to Amazon CloudFront.");
      }
   }
}
