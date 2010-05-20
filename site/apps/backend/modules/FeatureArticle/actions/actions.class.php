<?php

require_once dirname(__FILE__).'/../lib/FeatureArticleGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/FeatureArticleGeneratorHelper.class.php';

/**
 * FeatureArticle actions.
 *
 * @package    LoveToKnow
 * @subpackage FeatureArticle
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FeatureArticleActions extends autoFeatureArticleActions
{
    public function execute($request)
    {
          $channel_id = $request->getParameter('channel_id');
          if($channel_id > 0)
          {
             $this->forward404Unless($this->channel = Doctrine::getTable('Channel')->find($channel_id));
             $this->getContext()->getRouting()->setDefaultParameter('channel_id', $channel_id);
          }

          return parent::execute($request);
    }

    public function executeFeature(sfWebRequest $request)
    {

      $this->getResponse()->setContentType('application/json');

      $titles = Doctrine::getTable('Title')->filterTitleByChannel($this->channel, $request->getParameter('term'),"Article", 10);

      $results = array();

      foreach($titles as $title)
      {
         $results[] = array('thumb' => $title->title, 'label' => $title->title, 'value' => $title->id);
      }

      return $this->renderText(json_encode($results));
    }

    public function executeCategory(sfWebRequest $request)
    {

      $this->getResponse()->setContentType('application/json');

      $titles = Doctrine::getTable('Title')->filterTitleByChannel($this->channel, $request->getParameter('term'),"Category", 10);

      $results = array();

      foreach($titles as $title)
      {
         $results[] = array('thumb' => $title->title, 'label' => $title->title, 'value' => $title->id);
      }

      return $this->renderText(json_encode($results));
    }

    protected function buildQuery()
    {
      $query = parent::buildQuery();

      if($this->channel)
      {
         $query->addWhere('channel_id = ?', $this->channel->id);
      }

      return $query;
    }
}
