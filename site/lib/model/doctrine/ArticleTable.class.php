<?php

class ArticleTable extends Doctrine_Table
{

   /**
   * Lookup an article with it's URL.
   *
   * <b>Note:</b> if the $url parameter is an array, the $channel parameter is ignored and
   * the $url array is assumed to have two keys: 'url' and 'channel'.
   *
   * @param string $url the URL of the article
   * @param mixed $channel the channel that the article belongs to; can be a Channel object, channel id, or channel slug
   * @return Article
   */
   public function findOneByUrl($url, $channel = null)
   {
      // need the type check so we dont lookup category description articles
      $query = $this->createQuery('a')->innerJoin('a.Title t')->where('t.type = ?', 'Article');
      return Doctrine::getTable('Title')->getByUrlQuery($url, $channel, $query, 't')->fetchOne();
   }
   
   public function fetchCategoryArticlesByChannel(Channel $channel)
   {
      return $this->createQuery('a')
          ->select('t.title,t.url,tc.category_id as category_id,t1.title as categoryTitle,t1.url as categoryUrl,a.*')
          ->leftJoin('a.Title t')
          ->leftJoin('t.TitleCategory tc on t.id = tc.title_id')
          ->leftJoin('tc.Title t1 on t1.id = tc.category_id')
          ->where('t.channel_id = ?',$channel->id)
          ->andWhere("t.type = 'Article'")
          ->andWhere("a.show_home_page=1")
          ->addOrderBy('t1.title')
          ->execute();
   }
   
   public function ArticleCheckbox($id)
   {
        return $this->createQuery('a')
          ->select('show_home_page')
          ->where('a.id = ?',$id)
          ->fetchOne();
   }
 
}
