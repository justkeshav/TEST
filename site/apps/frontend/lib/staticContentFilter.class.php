<?php

class staticContentFilter extends sfFilter
{
   public function execute($filterChain)
   {
      // Execute next filter
      $filterChain->execute();

      $host = sfConfig::get('app_static_host');

      if(!empty($host))
      {
         $response = $this->context->getResponse();
         $content = $response->getContent();
         $content = preg_replace('/(src|href)\=\"\/(css|images|js)\//', '\1="http://' . $host . '/\2/', $content);
         $response->setContent($content);
      }
   }
}

