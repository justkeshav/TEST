<?php

class FeatureArticleTable extends Doctrine_Table
{
   public function checkAlreadyExist($id)
   {
        return $this->createQuery('fa')
         ->select('id')
         ->where('channel_id = ?', $id)                  
         ->fetchOne();
   }
    
   public function fetchFeatureArticlesByChannel(Channel $channel)
   {
         return $this->createQuery('fa')
          ->select('t.title as title,t.url as url,fa.category_id as category_id,fa.position as position')
          ->leftJoin('fa.Title t on t.id = fa.article_id')
          ->where('fa.channel_id = ?',$channel->id)
          ->execute();
   }
}
