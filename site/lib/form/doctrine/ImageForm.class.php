<?php

/**
 * Image form.
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ImageForm extends BaseImageForm
{
   public function setup()
   {
      parent::setup();

      unset($this['host']);
      unset($this['path']);
      unset($this['sizes']);
      unset($this['filename']);
      unset($this['updated_at']);
      unset($this['created_at']);
   }
}
