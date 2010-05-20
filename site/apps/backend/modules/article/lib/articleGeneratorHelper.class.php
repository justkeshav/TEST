<?php

/**
 * article module helper.
 *
 * @package    LoveToKnow
 * @subpackage article
 * @author     Your name here
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class articleGeneratorHelper extends BaseArticleGeneratorHelper
{
   public function linkToPreview($article, $params)
   {
      if ($article->isNew())
      {
         return '';
      } 
      
      $host = sfConfig::get('app_host');    
      return "<a id='article_preview' href=http://{$article->Channel->slug}.$host/{$article->Title->url}?preview=true>Preview</a>";
   }
}
