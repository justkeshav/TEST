<?php

require_once(dirname(__FILE__).'/../bootstrap/Doctrine.php');

abstract class WikiChannelImportBatch extends DoctrineBatch
{
   protected function process()
   {
      global $argv;

      $this->logInfo("Args: " . implode(' ', $argv));

      $this->db = Doctrine_Manager::connection()->getDbh();

      if($argv[1] != '--channel')
      {
         // this script is not calling itself so cycle through available channels and import them individually -- this works around memory leak issues
         $this->importChannels(array_slice($argv, 1));
      }
      else
      {
         // this script called itself and passed in cli parameters to import a single channel
         $this->importChannel($argv[2], $argv[3], $argv[4]);
      }
   }

   protected function importChannels($slugs)
   {
      $slugs = implode("', '", $slugs);

      $this->logNotice("Importing channels '$slugs'");

      $channels = Doctrine_Manager::connection()->fetchAssoc("
         select
            c.wiki_uri,
            concat('OLD_', c.database) as `database`,
            replace(replace(c.description, 'http://', ''), '.lovetoknow.com', '') as slug
         from
            `OLD_ltk_master`.channels c
         where
            c.active = 1
            and replace(replace(c.description, 'http://', ''), '.lovetoknow.com', '') in ('$slugs')");

      foreach($channels as $channel)
      {
         $cmd = sprintf(
            'php ' . $this->getScriptFile() . ' --channel "%s" "%s" "%s"',
            $channel['slug'],
            $channel['database'],
            $channel['wiki_uri'] == 1 ? 'wiki/' : '');

         $this->logInfo("Executing cmd '$cmd'");

         passthru($cmd);
      }

      $this->logNotice("Completed importing channels");
   }

   protected function importChannel($slug, $database, $urlPrefix)
   {
      $this->logNotice("Importing channel '$slug'");

      $this->channel = Doctrine::getTable('Channel')->findOneBy('slug', $slug);
      $this->database = $database;
      $this->urlPrefix = $urlPrefix;

      $this->doImport();

      $this->logNotice("Completed importing channel '$slug'");
   }

   abstract protected function doImport();

   abstract protected function getScriptFile();

   // convert wiki syntax to html
   // article passed by reference so props can be changed here
   
   public function wiki2html(Article &$article, $wiki)
   {
      
      $blockElements = 'h2|h3|p';

      $html = iconv("UTF-8", "CP1252//TRANSLIT", $wiki);

      // normalize line-endings
      $html = str_replace("\r\n", "\n", $html);
      
      // remove category designators at end of page
      $html = preg_replace('/^\[\[Category:([^\|]+)\]\]$/m', '', $html);   

      // convert tables 
      $html = convertTables($html);      

      // headings; order matters
      $html = preg_replace('/^====(.+)====\s*$/m', '<h4>$1</h4>', $html);   
      $html = preg_replace('/^===(.+)===\s*$/m', '<h3>$1</h3>', $html);
      $html = preg_replace('/^==(.+)==\s*$/m', '<h2>$1</h2>', $html);
      
      // un-ordered/bulleted lists
      $html = replace_bullet_lists($html); 

      // ordered/numbered lists
      $html = replace_numbered_lists($html);  
      
      // bold/italics; bold; italics; order matters
      $html = preg_replace("/''''([^']+)''''/m", '<strong><em>$1</em></strong>', $html); 
      $html = preg_replace("/'''([^']+)'''/m", '<strong>$1</strong>', $html); 
      $html = preg_replace("/''([^']+)''/m", '<em>$1</em>', $html);    

      // replace external links
      if (preg_match_all('/\[http[^\]]+\]/', $html, $wikiLinks, PREG_OFFSET_CAPTURE) != 0)
      {  
         $wikiLinks = $wikiLinks[0];
         
         foreach($wikiLinks as $wikiLink)
         {
            $link =  str_replace('[', '', $wikiLink[0]);
            $link =  str_replace(']', '', $link);

            $linkParts = explode(' ', $link, 2);
            
            $url = $linkParts[0];
            $anchor = $linkParts[1];
            
            // link to a non LTK site
            if (strpos($url, $hostname) == false)
            {
               $rel = "rel='nofollow'";
            }
            else
            {
               $rel = '';
            }            
            $html = str_replace($wikiLink[0], "<a href='{$url}' {$rel}>{$anchor}</a>", $html);
         }
      }
     
      // replace internal links      
      if (preg_match_all('/\[\[[^\[^\]]+\]\]/', $html, $wikiLinks, PREG_OFFSET_CAPTURE) != 0)
      {      
         $wikiLinks = $wikiLinks[0];
         
         foreach($wikiLinks as $wikiLink)
         {
            if (stripos($wikiLink[0], 'image') === false)
            {
               $link =  str_replace('[', '', $wikiLink[0]);
               $link =  str_replace(']', '', $link);
               
               $linkParts = explode('|', $link);
               
               $url = str_replace(': ', ':', $linkParts[0]);
               $url = str_replace(' ', '_', trim($url));
               
               if (isset($linkParts[1]))
               {
                  $anchor = $linkParts[1];
               }
               else
               {
                  $anchor = $linkParts[0];
                  $separator = strpos($anchor, ': ');
                  if ($separator)
                  {
                     $anchor = substr($anchor, $separator + 2);
                  }
               }
               
               $title = str_replace('_', ' ', $url);
               
               $html = str_replace($wikiLink[0],
                                    "<a href='/{$this->urlPrefix}{$url}' title='{$title}'>{$anchor}</a>",
                                    $html);
            }
         }
      }
      // replace image links
      if (preg_match_all('/\[\[.mage:[^\]]+\]\]\]?/', $html, $wikiLinks, PREG_OFFSET_CAPTURE) != 0)
      {  
         $wikiLinks = $wikiLinks[0];
         
         foreach($wikiLinks as $imageKey => $wikiLink)
         {
            $link =  str_replace('[', '', $wikiLink[0]);
            $link =  str_replace(']', '', $link);

            $htmlLink = null;
            
            // replace image links
            if (stripos($link, 'image:') === 0)
            {
               $linkParts = explode('|', substr($link, 6));
               
               foreach($linkParts as $key => $linkPart)
               {
                  if ($key == 0)
                  {
                     $imageParts['filename'] = str_replace(' ', '_', trim($linkPart));
                  }
                  else if ($linkPart == 'right' || $linkPart == 'left' || $linkPart == 'center')
                  {
                     $imageParts['float'] = $linkPart;
                  }
                  else if ($linkPart == 'adright')
                  {
                     $imageParts['float'] = $linkPart;
                  }                  
                  else if ($linkPart == 'thumb')
                  {
                     $imageParts['thumbnail'] = true;
                  }
                  else if (strpos($linkPart, 'px') > 0)
                  {
                     $imageParts['width'] = str_replace('px', '', $linkPart);
                  }
                  else if (strpos($linkPart, '<a') === 0)
                  {
                     if (preg_match("/<a href='([^']+)'>([^<]+)<\/a>/", $linkPart, $parts))
                     {
                        $imageParts['url'] = $parts[1];
                        $imageParts['caption'] = $parts[2];
                     }
                  }
                  else
                  {
                     $imageParts['text'] = $linkPart;
                  }
               }   

               // set primary image on title
               if ($imageKey == 0)
               {
                  $image = Doctrine::getTable('Image')->findOneBy('filename', $imageParts['filename']);
                  if ($image)
                  {               
                     $article->Title->image_id = $image->id;

                     if(!$image->hasSize('article'))
                     {
                        $width = (isset($imageParts['width']) ? $imageParts['width'] : 300);
                        
                        if (ltksfImageProvider::getInstance()->createSize($image, 'article', $width))
                        {
                           $image->save($con);
                        }
                        else
                        {
                           $this->logError("There was a problem saving the article image (wiki2html).");                   
                        }
                     }                     
                  }
                  $article->Title->image_link = $imageParts['url'];                  
                  $article->Title->image_text = $imageParts['text'];
                  $article->Title->image_caption = $imageParts['caption'];
                  $article->Title->image_width = $imageParts['width'];
                  $article->Title->image_thumbnail = $imageParts['thumbnail'];
                  
                  $htmlLink = '';
               }
               // set html for secondary image in article text
               else
               {               
                  $image = Doctrine::getTable('Image')->findOneBy('filename', $imageParts['filename']);
                  if ($image)
                  {
                     if(!$image->hasSize('article'))
                     {
                        $width = (isset($imageParts['width']) ? $imageParts['width'] : 300);
                        
                        if(ltksfImageProvider::getInstance()->createSize($image, 'article', $width))
                        {
                           $image->save($con);
                        }
                        else
                        {
                           $this->logError("There was a problem saving the article image (wiki2html).");                   
                        }
                     }                     

                     if (isset($imageParts['thumbnail']))
                     {
                        $imageUrl = $image->genUrl('thumb');
                     }
                     else
                     {
                        $imageUrl = $image->genUrl('article');
                     }
                     
                     if (!isset($imageParts['url']))
                     {
                        $imageParts['url'] = $imageUrl;
                     }               
                     if (!isset($imageParts['caption']) && isset($imageParts['text']))
                     {
                        $imageParts['caption'] = $imageParts['text'];
                     }                        
                     
                     if (isset($imageParts['caption']))
                     {
                         $imageParts['caption'] = '<span class="image_caption">
                                                      <a href="'.$imageParts['url'].'" title="'.$imageParts['text'].'">'
                                                         .$imageParts['caption'].'
                                                      </a>
                                                   </span>';
                     }

                     $htmlLink = '<div class="image_'.$imageParts['float'].'">
                                    <a href="'.$imageParts['url'].'" title="'.$imageParts['text'].'">
                                       <img src="'.$imageUrl.'"/>
                                    </a>'
                                    .$imageParts['caption'].'
                                 </div>';
                  
                  }
               }
            }
            
            $html = str_replace($wikiLink[0], $htmlLink, $html);       
                
         }
      }
      
      // finally, create paragraphs for all remaining blocks of content that we have not
      // already made into a non-paragraph; regex details:
      //
      // (^\s*|\n\n|(?:<\/' . $blockElements . ')>\n)
      // Find the start of the paragraph if the prior line is any of:
      // * the beginning of the content
      // * an empty line
      // * the end of a block element
      //
      // (?!<' . $blockElements . ')(.+?)(?!<\/' . $blockElements . '>)
      // But dont match if the block of content is already a block element
      //
      // (\s*$|\n\n|\n<(?:' . $blockElements . '))
      // Find the end of the paragraph if the following line is any of:
      // * the end of the content
      // * an empty line
      // * the beginning of a block element
      $html = preg_replace('/(^\s*|\n\s*\n|(?:<\/' . $blockElements . ')>\n)(?!<' . $blockElements . ')(.+?)(?!<\/' . $blockElements . '>)(\s*$|\n\n|\n<(?:' . $blockElements . '))/', '$1<p>$2</p>$3', $html);

      $article->text = $html;
   }
}

function replace_bullet_lists($text)
{
   $level1 = 0;
   $level2 = 0;
   $level3 = 0;

   $lines = explode("\n", $text);
   $html = '';
   
   foreach($lines as $line)
   {
      if (substr($line, 0, 3) == '***')
      {
         $opentag = ($level3 == 0 ? '<ul>' : '');
         
         $line = $opentag.'<li>'.substr($line, 3).'</li>';
         
         $level3 = 1;
      }
      else if (substr($line, 0, 2) == '**')
      {
         $closetag = ($level3 == 1 ? '</ul>' : '');
         if ($closetag)
         {
            $level3 = 0;
         }
         
         $opentag = ($level2 == 0 ? '<ul>' : '');   
         
         $line = $closetag.$opentag.'<li>'.substr($line, 2).'</li>';

         $level2 = 1;               
      }
      else if (substr($line, 0, 1) == '*')
      {
         $closetag = ($level2 == 1 ? '</ul>' : '');
         if ($closetag)
         {
            $level2 = 0;
         }
         $opentag = ($level1 == 0 ? '<ul>' : '');  
         
         $line = $closetag.$opentag.'<li>'.substr($line, 1).'</li>';

         $level1 = 1;          
      }
      else
      {
         $closetag = ($level1 == 1 ? "</ul>\n" : '');
         if ($closetag)
         {
            $level1 = 0;
         }
         $line = $closetag.$line;
      }
      $html = $html.$line."\n";
   }
   return $html;
}

function replace_numbered_lists($text)
{
   $level1 = 0;
   $level2 = 0;
   $level3 = 0;

   $lines = explode("\n", $text);
   $html = '';
   
   foreach($lines as $line)
   {
      if (substr($line, 0, 3) == '###')
      {
         $opentag = ($level3 == 0 ? '<ol>' : '');
         
         $line = $opentag.'<li>'.substr($line, 3).'</li>';
         
         $level3 = 1;
      }
      else if (substr($line, 0, 2) == '##')
      {
         $closetag = ($level3 == 1 ? "</ol>\n" : '');
         if ($closetag)
         {
            $level3 = 0;
         }
         
         $opentag = ($level2 == 0 ? '<ol>' : '');   
         
         $line = $closetag.$opentag.'<li>'.substr($line, 2).'</li>';

         $level2 = 1;               
      }
      else if (substr($line, 0, 1) == '#')
      {
         $closetag = ($level2 == 1 ? '</ol>' : '');
         if ($closetag)
         {
            $level2 = 0;
         }
         $opentag = ($level1 == 0 ? '<ol>' : '');  
         
         $line = $closetag.$opentag.'<li>'.substr($line, 1).'</li>';

         $level1 = 1;          
      }
      else
      {
         $closetag = ($level1 == 1 ? '</ol>' : '');
         if ($closetag)
         {
            $level1 = 0;
         }
         $line = $closetag.$line;
      }
      $html = $html.$line."\n";
   }
   return $html;
}

function convertTables($text)
{ 
   $text = preg_replace('/^\{\|(.*)$/m', '<table $1>', $text);   
   $text = preg_replace('/^\|\}(.*)$/m', '</table>', $text);     
   $text = preg_replace('/^\|\+(.*)$/m', '<caption>$1</caption>', $text); 
   $text = preg_replace('/^\|-$/m', '</tr>$1', $text);   
   $text = preg_replace('/^\|(.*)$/m', '<td>$1</td>', $text);

   while (preg_match('/^<td>([^\|]+)\|\|(.*)$/m', $text))
      $text = preg_replace('/^<td>([^\|]+)\|\|(.*)$/m', '<td>$1</td><td>$2', $text);

   $text = preg_replace('/^<td>([^\|]+)\|(.+)$/m', "<td $1>$2", $text);
   $text = preg_replace('/^<td>([^<]+)>(.+)$/m', "<td $1>$2", $text);
   
   $text = preg_replace('/<\/caption>\n<\/tr>/', "</caption>\n<tr>", $text);
   $text = preg_replace('/<\/tr>\n<td/', "</tr>\n<tr>\n<td", $text);
   
   return $text;
}

// TODO: duped some wiki2html code here for slideshows. need to consolidate with above method wiki2html()
function wiki2html($wiki)
{
   $hostname = sfConfig::get('app_host');
   
   $blockElements = 'h2|h3|p';

   $html = $wiki;

   // normalize line-endings
   $html = str_replace("\r\n", "\n", $html);

   // headings; order matters
   $html = preg_replace('/^====(.+)====$/m', '<h3>$1</h3>', $html);   
   $html = preg_replace('/^===(.+)===$/m', '<h3>$1</h3>', $html);
   $html = preg_replace('/^==(.+)==$/m', '<h2>$1</h2>', $html);

   // un-ordered/bulleted lists
   $html = replace_bullet_lists($html); 

   // ordered/numbered lists
   $html = replace_numbered_lists($html);  
   
   // bold/italics; bold; italics; order matters
   $html = preg_replace("/''''(.+)''''/m", '<strong><em>$1</em></strong>', $html); 
   $html = preg_replace("/'''(.+)'''/m", '<strong>$1</strong>', $html); 
   $html = preg_replace("/''(.+)''/m", '<em>$1</em>', $html);    
   
   // finally, create paragraphs for all remaining blocks of content that we have not
   // already made into a non-paragraph; regex details:
   //
   // (^\s*|\n\n|(?:<\/' . $blockElements . ')>\n)
   // Find the start of the paragraph if the prior line is any of:
   // * the beginning of the content
   // * an empty line
   // * the end of a block element
   //
   // (?!<' . $blockElements . ')(.+?)(?!<\/' . $blockElements . '>)
   // But dont match if the block of content is already a block element
   //
   // (\s*$|\n\n|\n<(?:' . $blockElements . '))
   // Find the end of the paragraph if the following line is any of:
   // * the end of the content
   // * an empty line
   // * the beginning of a block element
   $html = preg_replace('/(^\s*|\n\s*\n|(?:<\/' . $blockElements . ')>\n)(?!<' . $blockElements . ')(.+?)(?!<\/' . $blockElements . '>)(\s*$|\n\n|\n<(?:' . $blockElements . '))/', '$1<p>$2</p>$3', $html);

   $article->text = $html;
}
