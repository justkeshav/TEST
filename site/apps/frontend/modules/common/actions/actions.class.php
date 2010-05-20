<?php

class commonActions extends sfActions
{
   public function executeShowContent(sfWebRequest $request)
   {
      $this->title = $this->getRoute()->getObject();
    
      $this->preview = $request->getParameter('preview');

      // if the title has no content available to publish or preview, show 404
      $this->forward404Unless($this->title->getPublishedContent($this->preview) instanceof $this->title->typeClassName);
   }
}
