<?php

class TitleTable extends Doctrine_Table
{
   /**
   * Lookup a title with it's URL.
   *
   * <b>Note:</b> if the $url parameter is an array, the $channel parameter is ignored and
   * the $url array is assumed to have two keys: 'url' and 'channel'.
   *
   * @param string $url the URL of the title
   * @param mixed $channel the channel that the category belongs to; can be a Channel object, channel id, or channel slug
   * @return Title
   */
   public function findOneByUrl($url, $channel = null)
   {
      return $this->getByUrlQuery($url, $channel)->fetchOne();
   }

   /**
   * Modifies a query so that it will only select/affect titles with a specific url. If no query is passed
   * in it will create a select query.
   *
   * <b>Note:</b> if the $url parameter is an array, the $channel parameter is ignored and
   * the $url array is assumed to have two keys: 'url' and 'channel'.
   *
   * @param string $url the URL of the title
   * @param mixed $channel the channel that the title belongs to; can be a Channel object, channel id, or channel slug
   * @param Doctrine_Query $query the query to modify; omit to have this function create one
   * @param string $alias alias of the title table in the query; required if the Title model is not the root of the query
   * @return Doctrine_Query a query that will only select/affect titles of a specified url
   */
   public function getByUrlQuery($url, $channel = null, Doctrine_Query $query = null, $alias = null)
   {
      if(is_array($url))
      {
         $params = $url;
         $url = $params['url'];
         $channel = $params['channel'];
      }

      if(empty($url))
      {
         throw new Exception("Parameter 'url' is required.");
      }

      if(empty($channel))
      {
         throw new Exception("Parameter 'channel' is required.");
      }

      if(is_null($query))
      {
         $query = $this->createQuery('t')->select('t.*');
      }

      if(is_null($alias))
      {
         $alias = $query->getRootAlias();
      }

      $query->innerJoin("$alias.Channel channel")->andWhere("$alias.url = ?", $url);

      if($channel instanceof Channel)
      {
         $query->andWhere('channel.id = ?', $channel->id);
      }
      else if(is_numeric($channel))
      {
         $query->andWhere('channel.id = ?', intval($channel));
      }
      else
      {
         $query->andWhere('channel.slug = ?', "$channel");
      }

      $query->useResultCache(true)->setResultCacheHash("title|url={$url}");

      return $query;
   }

   /**
   * Modifies a query so that it will only select/affect titles with a specific type. If no query is passed
   * in it will create a select query.
   *
   * @param string $type the type to filter on
   * @param Doctrine_Query $query the query to modify; omit to have this function create one
   * @param string $alias alias of the title table in the query; required if the Title model is not the root of the query
   * @return Doctrine_Query a query that will only select/affect titles of a specified type
   */
   public function getByTypeQuery($type, Doctrine_Query $query = null, $alias = null)
   {
      if(is_null($query))
      {
         $query = $this->createQuery('t')->select('t.*');
      }

      if(is_null($alias))
      {
         $alias = $query->getRootAlias();
      }

      return $query->andWhere("$alias.type = ?", $type);
   }

   public function fetchLatestArticlesByChannel(Channel $channel)
   {
      return $this->createQuery('t')
          ->select('t.title,t.url,t1.title as categoryTitle,t1.url as categoryUrl')
          ->leftJoin('t.TitleCategory tc')
          ->leftJoin('tc.Title t1 on t1.id = tc.category_id')
          ->where('t.channel_id = ?',$channel->id)
          ->andWhere("t.type = 'Article'")
          ->addOrderBy('t.created_at DESC')
          ->limit(6)
          ->execute();
   }

   public function searchByFilename($channel, $partialFilename,$type, $max = 10)
   {
      return $this->createQuery('t')
         ->select('t.title,t.url,t.id')
         ->where('channel_id = ?', $channel->id)
         ->andWhere('title like ?', "%$partialFilename%")
         ->andWhere("t.type = ?","$type")
         ->limit($max)
         ->execute();
   }

   public function filterTitleByChannel($channel, $partialFilename,$type, $max = 10)
   {
      return $this->createQuery('t')
         ->select('t.title,t.url,t.id')
         ->where('channel_id = ?', $channel->id)
         ->andWhere("t.type = ?","$type")
         ->andWhere('title like ?', "%$partialFilename%")
         ->limit($max)
         ->execute();
   }

   public function getTitleById($id)
   {
       return $this->createQuery('t')
         ->select('t.title as title,t.url as url,c.title as channel')
         ->leftJoin('t.Channel c')
         ->where('id = ?', $id)
         ->andWhere("type = 'Article'")
         ->fetchone();
   }

   public function getIdByTitle($title)
   {
       return $this->createQuery('t')
         ->select('t.id as id')
         ->where('title = ?', $title)
         ->andWhere("type = 'Article'")
         ->fetchone();
   }
}
