<?php

class articleComponents extends sfComponents
{
   public function executeShow(sfWebRequest $request)
   {
      $this->article = $this->content;
   }
}
