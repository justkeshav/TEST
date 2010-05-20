<?php

/**
 * channel actions.
 *
 * @package    LoveToKnow
 * @subpackage channel
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class channelActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
  }
  public function executeShow(sfWebRequest $request)
  {
      $this->channel = Doctrine::getTable('Channel')->findOneBySlug($request->getParameter('channel'));
      $this->latestArticles = Doctrine::getTable('Title')->fetchLatestArticlesByChannel($this->channel);            
      $this->slideShows = Doctrine::getTable('Slideshow')->fetchSlideshowsByChannel($this->channel);
      $this->categoryArticles = Doctrine::getTable('Article')->fetchCategoryArticlesByChannel($this->channel);
      $this->featureArticles = Doctrine::getTable('FeatureArticle')->fetchFeatureArticlesByChannel($this->channel);
  }
}
