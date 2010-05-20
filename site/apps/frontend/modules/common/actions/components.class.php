<?php

class commonComponents extends sfComponents
{
   /**
   * Loads data needed todisplay the common header
   *
   * @param sfRequest $request A request object
   */
   public function executeHeader(sfWebRequest $request)
   {
      $this->verticals = Doctrine::getTable('Vertical')->findAll();      
   }
   public function executeFooter(sfWebRequest $request)
   {      
      $this->channel = Doctrine::getTable('Channel')->findOneBySlug($request->getParameter('channel'));
      $this->channelDetail1 = Doctrine::getTable('ChannelDetails')->fetchAboutChannelArticle($this->channel);
   }
}
