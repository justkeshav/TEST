<?php

class categoryComponents extends sfComponents
{
   public function executeShow(sfWebRequest $request)
   {
      $this->category = $this->content;
   }

   /**
   * Loads data needed to display the common header
   *
   * @param sfRequest $request A request object
   */
   public function executeSidebar(sfWebRequest $request)
   {
      $this->channel = Doctrine::getTable('Channel')->findOneBySlug($request->getParameter('channel'));
      $this->categories = Doctrine::getTable('Category')->fetchTreeByChannel($this->channel, 1);
   }
}
