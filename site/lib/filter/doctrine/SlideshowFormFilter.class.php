<?php

/**
 * Slideshow filter form.
 *
 * @package    LoveToKnow
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SlideshowFormFilter extends BaseSlideshowFormFilter
{
   public function configure()
   {
      unset($this['title_id']);
   }
}
