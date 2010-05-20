<?php

class ChannelDetailsTable extends Doctrine_Table
{
    public function checkAlreadyExist($id)
    {
        return $this->createQuery('cd')
         ->select('channel_id')
         ->where('channel_id = ?', $id)
         ->fetchOne();
    }
    
    public function fetchAboutChannelArticle(Channel $channel)
    {
        return $this->createQuery('cd')
          ->select('ac.title as about_channel_article,ac.url as about_channel_article_url')
          ->leftJoin('cd.AboutChannel ac on ac.id = cd.about_title_id')
          ->where('cd.channel_id = ?',$channel->id)
          ->fetchOne();
    }
}
