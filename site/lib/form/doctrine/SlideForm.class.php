<?php

/**
 * Slide form.
 *
 * @package    LoveToKnow
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SlideForm extends BaseSlideForm
{
   public function setup()
   {
      parent::setup();

      unset($this['updated_at']);
      unset($this['created_at']);
      unset($this['updated_by']);
      unset($this['created_by']);
      unset($this['version']);
   }
}
