<?php

class CategoryTable extends Doctrine_Table
{
   public function fetchTreeByChannel(Channel $channel, $depth = null)
   {
      $query = $this->createQuery('c')->select('c.*, t.title, t.url')->innerJoin('c.Title t')->where('c.level > 0');

      $treeObject = $this->getTree();
      $treeObject->setBaseQuery($query);
      $tree = $treeObject->fetchTree(array('root_id' => $channel->id, 'depth' => $depth));
      $treeObject->resetBaseQuery();

      return $tree;
   }

   public function findRootByChannelId($channelId)
   {
      return $this->createQuery('cat')
         ->where('cat.channel_id = ?', $channelId)
         ->andWhere('level = ?', 0)
         ->fetchOne();
   }

   /**
   * Lookup a category with it's URL.
   *
   * <b>Note:</b> if the $url parameter is an array, the $channel parameter is ignored and
   * the $url array is assumed to have two keys: 'url' and 'channel'.
   *
   * @param string $url the URL of the category
   * @param mixed $channel the channel that the category belongs to; can be a Channel object, channel id, or channel slug
   * @return Category
   */
   public function findOneByUrl($url, $channel = null)
   {
      $query = $this->createQuery('c')->innerJoin('c.Title t');
      return Doctrine::getTable('Title')->getByUrlQuery($url, $channel, $query, 't')->fetchOne();
   }
}
