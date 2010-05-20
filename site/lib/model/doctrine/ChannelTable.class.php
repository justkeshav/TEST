<?php

class ChannelTable extends Doctrine_Table
{
   public function findOneBySlug($slug)
   {
      return $this->createQuery()->where('slug = ?', $slug)->useResultCache(true)->setResultCacheHash("channel|slug={$slug}")->fetchOne();
   }
}
