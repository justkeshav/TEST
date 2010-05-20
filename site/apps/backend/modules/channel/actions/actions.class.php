<?php

require_once dirname(__FILE__).'/../lib/channelGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/channelGeneratorHelper.class.php';

/**
 * channel actions.
 *
 * @package    LoveToKnow
 * @subpackage channel
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class channelActions extends autoChannelActions
{
   public function execute($request)
   {
      $vertical_id = $request->getParameter('vertical_id');

      if($vertical_id > 0)
      {
         $this->forward404Unless($this->vertical = Doctrine::getTable('Vertical')->find($vertical_id));
         $this->getContext()->getRouting()->setDefaultParameter('vertical_id', $vertical_id);
      }

      return parent::execute($request);
   }

   protected function buildQuery()
   {
      $query = parent::buildQuery();

      if($this->vertical)
      {
         $query->addWhere('vertical_id = ?', $this->vertical->id);
      }

      return $query;
   }
}
