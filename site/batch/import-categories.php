<?php
/**
* Batch process to import category and article data from the old MediaWiki databases.
*/

require_once(dirname(__FILE__).'/helpers/wiki.php');

class CategoryImportBatch extends WikiChannelImportBatch
{
   protected function getScriptFile()
   {
      return __FILE__;
   }

   protected function doImport()
   {
      $this->importImages();
      $this->importCategories();
      $this->buildCategoryTree();
   }

   private function importImages()
   {
      $this->logNotice("Importing images");

      $images = Doctrine_Manager::connection()->fetchAssoc("
         select
            i.img_name, i.img_description, i.img_user, i.img_user_text, i.img_timestamp as created
         from
            `{$this->database}`.image i
         ");

      foreach($images as $image)
      {
         $this->importImage($image['img_name'], $image['img_description'], $image['img_user'], $image['img_user_text'], strtotime($image['img_timestamp']));
      }
   }

   private function importImage($filename, $description, $user, $text, $created)
   {
      $this->logNotice("Importing image $this->slug, $filename");

      $created = date('Y-m-d H:i:s', $created);

      $image = new Image();

      $image->channel_id = $this->channel->id;
      $image->permission = 'See description';
      $image->source = 'See description';
      $image->description = $description;

      $wikiFolders = md5($filename);

      $wikiImageFile = "/shared/lovetoknow/wwwvhost/channels/images/".$this->channel->title."/".substr($wikiFolders, 0, 1)."/".substr($wikiFolders,0, 2)."/".$filename;
      $tempImageFile = sfConfig::get('sf_web_dir').'/uploads/'.$filename;

      $fileType = pathinfo($tempImageFile);
      $fileType = strtolower($fileType['extension']);

      if (file_exists($wikiImageFile) && ($fileType == 'jpg' || $fileType == 'jpeg' || $fileType == 'gif' || $fileType == 'png'))
      {
         if (copy($wikiImageFile, $tempImageFile) == true)
         {
            if(!ltksfImageProvider::getInstance()->create($tempImageFile, $image))
            {
               $this->logError("There was a problem saving the image file '$tempImageFile'.");
            }
         }
         else
         {
            $this->logError("Could not copy to temp file '$wikiImageFile' to '$tempImageFile'.");
         }
      }
   }

   private function importCategories()
   {
      $this->logNotice("Importing categories");

      $this->categories = array();

      $categoryData = Doctrine_Manager::connection()->fetchAssoc("
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
            and p.page_namespace = 14
         group by
            p.page_id");

      foreach($categoryData as $c)
      {
         $this->importCategory($c['page_title'], $c['old_text'], strtotime($c['created']), strtotime($c['updated']));
      }
   }

   private function importCategory($title, $text, $created, $updated)
   {
      $this->logNotice("Importing category '$title'");

      $created = date('Y-m-d H:i:s', $created);
      $updated = date('Y-m-d H:i:s', $updated);

      $category = new Category();
      $category->Channel = $this->channel;
      $category->Title->Channel = $this->channel;
      $category->title = str_replace('_', ' ', $title);
      $category->url = "{$this->urlPrefix}Category:$title";

      $category->Title->status = Title::STATUS_PUBLISHED;
      $category->Title->published_content_version = 1;
      $category->Title->author_user_id = 1;

      $category->created_at = $created;
      $category->updated_at = $updated;

      $category->Article = new Article();
      $category->Article->Title = $category->Title;
      $this->wiki2html($category->Article, $text);
      //default to the admin user for initial import purposes (will prob need to match actual users at a later date)
      $category->Article->created_at = $created;
      $category->Article->updated_at = $updated;

      $category->save();

      $category->Article->free();

      $this->categories[$title] = $category;
   }

   private function buildCategoryTree()
   {
      $this->logNotice("Building category tree");

      $root = new Category();
      $root->Channel = $this->channel;
      $root->save();

      $tree = Doctrine::getTable('Category')->getTree();
      $tree->createRoot($root);

      $treeData = Doctrine_Manager::connection()->fetchAssoc("
         select
            child.page_title as child_title, parent.page_title as parent_title
         from
            `{$this->database}`.page child
            left join `{$this->database}`.categorylinks c on child.page_id = c.cl_from
            left join `{$this->database}`.page parent on c.cl_to = parent.page_title
         where
            length(child.page_title) > 0
            and child.LTK_TYPE = ''
            and child.page_namespace = 14
            and
            (
               parent.page_id is null
               or
               (
                  length(parent.page_title) > 0
                  and parent.LTK_TYPE = ''
                  and parent.page_namespace = 14
               )
            )");

      foreach($treeData as $t)
      {
         $child = $this->categories[$t['child_title']];

         if($parent = $this->categories[$t['parent_title']])
         {
            $child->getNode()->insertAsLastChildOf($parent);
         }
         else
         {
            $child->getNode()->insertAsLastChildOf($root);
         }
      }
      $root->free();

   }

}

CategoryImportBatch::run();


/*


class CategoryAndArticleImportBatch extends WikiChannelImportBatch
{
   protected function getScriptFile()
   {
      return __FILE__;
   }

   protected function doImport()
   {
      $this->contentDatesStmt = $this->db->prepare('update content set created_at = ?, updated_at = ? where id = ?');
      $this->categoryDatesStmt = $this->db->prepare('update category set created_at = ?, updated_at = ? where id = ?');
      $this->articleDatesStmt = $this->db->prepare('update article set created_at = ?, updated_at = ? where id = ?');

      $this->importCategories();
      $this->importArticles();
      $this->buildCategoryTree();
   }

   private function importCategories()
   {
      $this->logNotice("Importing categories");

      $this->categories = array();

      $categoryData = Doctrine_Manager::connection()->fetchAssoc("
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
            and p.page_namespace = 14
         group by
            p.page_id");

      foreach($categoryData as $c)
      {
         $this->importCategory($c['page_title'], $c['old_text'], strtotime($c['created']), strtotime($c['updated']));
      }
   }

   private function importCategory($title, $text, $created, $updated)
   {
      $this->logNotice("Importing category '$title'");

      $created = date('Y-m-d H:i:s', $created);
      $updated = date('Y-m-d H:i:s', $updated);

      $category = new Category();
      $category->Channel = $this->channel;
      $category->Title->Channel = $this->channel;
      $category->title = str_replace('_', ' ', $title);
      $category->url = "{$this->urlPrefix}Category:$title";
      $category->created_at = $created;
      $category->updated_at = $updated;

      $category->Article = new Article();
      $category->Article->Title = $category->Title;
      $category->Article->text = wiki2html($text);
      $category->Article->created_at = $created;
      $category->Article->updated_at = $updated;

      $category->save();

      $category->Article->free();
      $category->Title->free();

      $this->categories[$title] = $category;
   }

   private function buildCategoryTree()
   {
      $this->logNotice("Building category tree");

      $root = new Category();
      $root->Channel = $this->channel;
      $root->save();

      $tree = Doctrine::getTable('Category')->getTree();
      $tree->createRoot($root);

      $treeData = Doctrine_Manager::connection()->fetchAssoc("
         select
            child.page_title as child_title, parent.page_title as parent_title
         from
            `{$this->database}`.page child
            left join `{$this->database}`.categorylinks c on child.page_id = c.cl_from
            left join `{$this->database}`.page parent on c.cl_to = parent.page_title
         where
            length(child.page_title) > 0
            and child.LTK_TYPE = ''
            and child.page_namespace = 14
            and
            (
               parent.page_id is null
               or
               (
                  length(parent.page_title) > 0
                  and parent.LTK_TYPE = ''
                  and parent.page_namespace = 14
               )
            )");

      foreach($treeData as $t)
      {
         $child = $this->categories[$t['child_title']];

         if($parent = $this->categories[$t['parent_title']])
         {
            $child->getNode()->insertAsLastChildOf($parent);
         }
         else
         {
            $child->getNode()->insertAsLastChildOf($root);
         }
      }

      $root->free();
   }

   private function importArticles()
   {
      $this->logNotice("Importing articles");

      $articleCategories = array();

      $articleCategoryData = Doctrine_Manager::connection()->fetchAssoc("
         select
            article.page_title as article_title, category.page_title as category_title
         from
            `{$this->database}`.page article
            join `{$this->database}`.categorylinks c on article.page_id = c.cl_from
            join `{$this->database}`.page category on c.cl_to = category.page_title
         where
            length(article.page_title) > 0
            and article.LTK_TYPE = ''
            and article.page_namespace = 0
            and article.page_is_redirect = 0
            and length(category.page_title) > 0
            and category.LTK_TYPE = ''
            and category.page_namespace = 14");

      foreach($articleCategoryData as $ac)
      {
         $articleCategories[$ac['article_title']][] = $this->categories[$ac['category_title']];
      }

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
            p.page_id");

      foreach($articles as $article)
      {
         $this->importArticle($articleCategories, $article['page_title'], $article['old_text'], strtotime($article['created']), strtotime($article['updated']));
      }
   }

   private function importArticle($articleCategories, $title, $text, $created, $updated)
   {
      $this->logNotice("Importing article '$title'");

      $created = date('Y-m-d H:i:s', $created);
      $updated = date('Y-m-d H:i:s', $updated);

      $article = new Article();
      $article->Channel = $this->channel;
      $article->title = str_replace('_', ' ', $title);
      $article->url = "{$this->urlPrefix}$title";
      $article->text = wiki2html($text);
      $article->created_at = $created;
      $article->updated_at = $updated;

      if(is_array($articleCategories[$title]))
      {
         foreach($articleCategories[$title] as $category)
         {
            $article->Categories[] = $category;
         }
      }

      $article->save();

      $article->Title->free();
      $article->free();
   }
}
*/

