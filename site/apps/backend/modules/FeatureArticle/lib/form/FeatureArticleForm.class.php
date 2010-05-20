<?php

class BackendFeatureArticleForm extends autoFeatureArticleForm
{
   public function configure()
   {      
      $channel = $this->getObject()->channel_id;      
      $this->widgetSchema['category_id'] = new ltksfWidgetFormFeatureArticleCat(array(
         'title' => $this->getObject()->category_id,
         'channel_id' => $channel));
      $this->widgetSchema['article_id'] = new ltksfWidgetFormFeatureArticle(array(
         'title' => $this->getObject()->article_id,
         'channel_id' => $channel));
      if ($this->getObject()->isNew())
      {
        $this->widgetSchema->setDefault('channel_id',$channel);
      }
      else
      {        
        $this->widgetSchema['category_id']->setAttribute('value',$this->getObject()->category_id);
        $this->widgetSchema['article_id']->setAttribute('value',$this->getObject()->article_id);
      }
   }
}
