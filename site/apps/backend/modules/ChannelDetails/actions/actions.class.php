<?php

require_once dirname(__FILE__).'/../lib/ChannelDetailsGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/ChannelDetailsGeneratorHelper.class.php';

/**
 * ChannelDetails actions.
 *
 * @package    LoveToKnow
 * @subpackage ChannelDetails
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ChannelDetailsActions extends autoChannelDetailsActions
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

    public function executeIndex(sfWebRequest $request)
    {
        $exist = Doctrine::getTable('ChannelDetails')->checkAlreadyExist($request->getParameter('channel_id'));
        if ($exist)
        {
            $this->redirect($this->generateUrl('channel_details').'/'.$request->getParameter('channel_id').'/edit');
        }
        else
        {
            $this->redirect($this->generateUrl('channel_details').'/new?channel_id='.$request->getParameter('channel_id'));
        }
    }

    public function executeBrowse(sfWebRequest $request)
   {
      $this->getResponse()->setContentType('application/json');

      $titles = Doctrine::getTable('Title')->searchByFilename($this->channel, $request->getParameter('term'),'Article', 10);

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
