<?php

class slideshowComponents extends sfComponents
{
   public function executeShow(sfWebRequest $request)
   {
      $this->slide = $this->title->content->Slides[$request->getParameter('page')];
   }
}
