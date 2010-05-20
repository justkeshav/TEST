<?php
/**
* Batch process to import slideshow data from the old MediaWiki databases.
*/

require_once(dirname(__FILE__).'/helpers/wiki.php');

class SlideshowImportBatch extends WikiChannelImportBatch
{
   protected function getScriptFile()
   {
      return __FILE__;
   }

   protected function doImport()
   {
      $this->logNotice("Importing slideshows");

      $this->setDefaultCategory();

      $slideshows = Doctrine_Manager::connection()->fetchAssoc("
         select
            p.page_id, p.page_title, s.SLIDE_SHOW_ID, s.TITLE, s.CATEGORY, s.CREATED_DATE as created, max(r.rev_timestamp) as updated
         from
            `OLD_ltk_master`.SLIDE_SHOWS s
            join `{$this->database}`.page p on s.TITLE = p.page_title or concat('Slideshow:', s.TITLE) = p.page_title
            join `{$this->database}`.revision r on p.page_id = r.rev_page
         where
            s.PUBLISH = 1
            and s.DELETED = 0
            and length(p.page_title) > 0
            and p.LTK_TYPE = 'S'
            and p.page_namespace = 0
         group by
            p.page_id");

      foreach($slideshows as $s)
      {
         $cats = $s['CATEGORY'];
         $cats = empty($cats) ? array() : explode(',', $cats);
         array_unshift($cats, $this->defaultCategory);
         $this->importSlideshow($s['page_title'], $s['SLIDE_SHOW_ID'], $s['TITLE'], $cats, strtotime($s['created']), strtotime($s['updated']));
      }
   }

   private function setDefaultCategory()
   {
      $name = Doctrine_Manager::connection()->fetchOne("
         select
            c.channel_name
         from
            `OLD_ltk_master`.channels c
         where
            replace(replace(c.description, 'http://', ''), '.lovetoknow.com', '') = ?",
         array($this->channel->slug));

      switch($name)
      {
         case "HomeImprovement": $this->defaultCategory = 'Home_Improvement'; break;
         case "EngagementRings": $this->defaultCategory = 'Engagement_Rings'; break;
         case "SanFrancisco": $this->defaultCategory = 'San_Francisco'; break;
         case "InteriorDesign": $this->defaultCategory = 'Interior_Design'; break;
         case "Greenliving": $this->defaultCategory = 'Green_Living'; break;
         case "SocialNetworking": $this->defaultCategory = 'Social_Networking'; break;
         case "Web-Design": $this->defaultCategory = 'Web_Design'; break;
         case "Boardgames": $this->defaultCategory = 'Board_Games'; break;
         case "Cellphones": $this->defaultCategory = 'Cell_Phones'; break;
         case "Creditcards": $this->defaultCategory = 'Credit_Cards'; break;
         case "Cellphones": $this->defaultCategory = 'Cell_Phones'; break;
         case "Plussize": $this->defaultCategory = 'Plus_Size'; break;
         case "Themeparks": $this->defaultCategory = 'Theme_Parks'; break;
         case "VideoGames": $this->defaultCategory = 'Video_Games'; break;
         case "Sci-Fi": $this->defaultCategory = 'Science_Fiction'; break;
         default: $this->defaultCategory = ucwords(str_replace("-", "_", $name));
      }

      $this->defaultCategory = "{$this->defaultCategory}_Slideshows";
   }

   private function importSlideshow($url, $old_id, $title, array $categories, $created, $updated)
   {
      $this->logNotice("Importing slideshow '$title'");

      $created = date('Y-m-d H:i:s', $created);
      $updated = date('Y-m-d H:i:s', $updated);

      $slideshow = new Slideshow();
      $slideshow->Channel = $this->channel;
      $slideshow->title = str_replace('_', ' ', $title);
      $slideshow->url = "{$this->urlPrefix}$url";

      $slideshow->Title->status = Title::STATUS_PUBLISHED;
      $slideshow->Title->published_content_version = 1;
      $slideshow->Title->author_user_id = 1;

      $slideshow->created_at = $created;
      $slideshow->updated_at = $updated;

      foreach($categories as $category)
      {
         $slideshow->Categories[] = Doctrine::getTable('Category')->findOneByUrl("{$this->urlPrefix}Category:$category", $this->channel);
      }

      $this->importSlides($old_id, $slideshow);

      $slideshow->save();

      $slideshow->free();
   }

   private function importSlides($old_id, $slideshow)
   {
      $this->logInfo("Importing slides");

      $slides = Doctrine_Manager::connection()->fetchAssoc("
         select
            s.IMAGE_NAME, s.IMAGE_TEXT, s.IMAGE_INFO, s.IMAGE_LINK, s.HEADING
         from
            `OLD_ltk_master`.SLIDES s
         where
            s.SLIDE_SHOW_ID = $old_id
         order by
            s.RANK, s.SLIDE_ID");

      $rank = 0;

      foreach($slides as $s)
      {
         $this->importSlide($slideshow, $rank++, $s['HEADING'], $s['IMAGE_TEXT'], "$old_id/" . $s['IMAGE_NAME'], $s['IMAGE_NAME'], $s['IMAGE_INFO'], $s['IMAGE_LINK']);
      }
   }

   private function importSlide($slideshow, $rank, $heading, $content, $filepath, $filename, $image_info, $image_link)
   {
      $this->logInfo("Importing slide '$heading'");

      $image = new Image();
      $image->channel_id = $this->channel->id;

      $tempFile = '/tmp/' . str_replace('/', '-', $filename);

      if(copy("/shared/lovetoknow/wwwvhost/channels/skins/Slide/Upload/$filepath", $tempFile))
      {
         if(ltksfImageProvider::getInstance()->create($tempFile, $image))
         {
            if(ltksfImageProvider::getInstance()->createSize($image, 'slide', 600, 500))
            {
               $slide = new Slide();
               $slide->Image = $image;
               $slide->image_link = $image_link;
               $slide->rank = $rank;
               $slide->heading = $heading;
               $slide->text = wiki2html($content);
               $slideshow->Slides[] = $slide;
            }
            else
            {
               $this->logError("Unable to create 'slide' size image from '$tempFile' for slide '$heading'");
            }
         }
         else
         {
            $this->logError("Unable to create slide image from '$tempFile' for slide '$heading'");
         }
      }
      else
      {
         $this->logError("Unable to copy slide image '$filepath' to '$tempFile' for slide '$heading'");
      }
   }
}

SlideshowImportBatch::run();
