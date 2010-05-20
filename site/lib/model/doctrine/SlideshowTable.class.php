<?php

class SlideshowTable extends Doctrine_Table
{
   /**
   * Modifies a query so that it will only select/affect published slideshows. If no query is passed
   * in it will also create a query.
   *
   * @param Doctrine_Query $query the query to modify; omit to have this function create one
   * @param string $alias alias of the slideshow table in the query; required if the Slideshow model is not the root of the query
   * @return Doctrine_Query a query that will only select/affect published slideshows
   */
   public function getPublishedQuery(Doctrine_Query $query = null, $alias = null)
   {
      if(is_null($query))
      {
         $query = $this->createQuery('s')->select('s.*');
      }

      if(is_null($alias))
      {
         $alias = $query->getRootAlias();
      }

      return $query->andWhere("$alias.published_at <= ?", date('Y-m-d H:i:s', time()));
   }
   
   public function fetchSlideshowsByChannel(Channel $channel)
   {
       return $this->createQuery('s')
          ->select('t.title,t.url,s.*')
          ->leftJoin('s.Title t on t.id = s.title_id')
          ->where('t.channel_id = ?',$channel->id)
          ->andWhere("t.type = 'slideshow'")
          ->andWhere("s.show_home_page = 1")
          ->addOrderBy('t.created_at DESC')
          ->limit(6)
          ->execute();
   }
}

