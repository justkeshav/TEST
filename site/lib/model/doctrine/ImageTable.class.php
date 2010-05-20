<?php

class ImageTable extends Doctrine_Table
{
   public function searchByFilename($channel, $partialFilename, $max = 10)
   {
      return $this->createQuery()
         ->where('channel_id = ?', $channel->id)
         ->andWhere('filename like ?', "%$partialFilename%")
         ->limit($max)
         ->execute();
   }
}
