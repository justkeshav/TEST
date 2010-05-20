<?php
/**
* Batch process to import category and article data from the old MediaWiki databases.
*/

require_once(dirname(__FILE__).'/helpers/wiki.php');

class ArticleImportBatch extends WikiChannelImportBatch
{
   protected function getScriptFile()
   {
      return __FILE__;
   }

   protected function doImport()
   {
      $this->importArticles();
      $this->importArticleCategories();
   }

   private function importArticleCategories()
   {
      $this->logNotice("Importing article categories");

      Doctrine_Manager::connection()->exec("
         insert into
            title_category (title_id, category_id, created_at, updated_at, version)
         select distinct
            at.id, c.id, NOW(), NOW(), 1
         from
            `{$this->database}`.page ap
            join `{$this->database}`.categorylinks cl on ap.page_id = cl.cl_from
            join `{$this->database}`.page cp on cl.cl_to = cp.page_title
            join title ct on ct.title = replace(cp.page_title, '_', ' ')
            join category c on c.title_id = ct.id
            join title at on at.title = replace(ap.page_title, '_', ' ')
            join article a on a.title_id = at.id
         where
            length(ap.page_title) > 0
            and ap.LTK_TYPE = ''
            and ap.page_namespace = 0
            and ap.page_is_redirect = 0
            and length(cp.page_title) > 0
            and cp.LTK_TYPE = ''
            and cp.page_namespace = 14
            and ct.channel_id = {$this->channel->id}
            and c.channel_id = {$this->channel->id}
            and at.channel_id = {$this->channel->id}");


      Doctrine_Manager::connection()->exec("
         insert into
            title_category_version (title_id, category_id, created_at, updated_at, version)
         select
            tc.title_id, tc.category_id, tc.created_at, tc.updated_at, tc.version
         from
            title_category tc
            join category c on tc.category_id = c.id
            join title t on tc.title_id = t.id
         where
            t.channel_id = {$this->channel->id}
            and c.channel_id = {$this->channel->id}");
   }

   private function importArticles()
   {
      $this->logNotice("Importing articles");

      $articles = Doctrine_Manager::connection()->fetchAssoc("
         select
            p.page_title, t.old_text, min(r2.rev_timestamp) as created, max(r2.rev_timestamp) as updated
         from
            `{$this->database}`.page p
            join `{$this->database}`.revision r on p.page_latest = r.rev_id
            join `{$this->database}`.text t on r.rev_text_id = t.old_id
            join `{$this->database}`.revision r2 on p.page_id = r2.rev_page
         where
            length(p.page_title) > 0
            and p.LTK_TYPE = ''
            and p.page_namespace = 0
            and p.page_is_redirect = 0
         group by
            p.page_id
         ");

      foreach($articles as $article)
      {
         $this->importArticle($article['page_title'], $article['old_text'], strtotime($article['created']), strtotime($article['updated']));
      }
  }

   private function importArticle($title, $text, $created, $updated)
   {
      $this->logNotice("Importing article '$title'");

      $created = date('Y-m-d H:i:s', $created);
      $updated = date('Y-m-d H:i:s', $updated);

      $article = new Article();
      $article->Channel = $this->channel;
      $article->title = str_replace('_', ' ', $title);
      $article->url = "{$this->urlPrefix}$title";
      $article->Title->status = Title::STATUS_PUBLISHED;
      $article->Title->published_content_version = 1;

      //TODO: default to the admin user for initial import purposes (will prob need to match actual users at a later date)
      $article->Title->author_user_id = 1;

      // article is passed by ref so wiki2html() can mod the obj properties directly
      $this->wiki2html($article, $text);
      $article->related_article_urls = $article->setRelatedArticles();
      $article->created_at = $created;
      $article->updated_at = $updated;
      $article->save();

      $article->Title->free();
      $article->free();
   }
}

ArticleImportBatch::run();

