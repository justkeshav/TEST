<?php

// need to use getRaw to prevent output escaping of HTML content
$text = $article->getRaw('text');

// default is to put one ad before the text and one ad after
$textBeforeAds = '';
$textBetweenAds = $text;
$textAfterAds = '';

// put first ad after the first paragraph if there are at least two paragraphs
$splitOnParagraphs = explode('</p>', $textBetweenAds, 3);
if(count($splitOnParagraphs) == 3)
{
   $textBeforeAds = $splitOnParagraphs[0] . '</p>';
   $textBetweenAds = $splitOnParagraphs[1] . '</p>' . $splitOnParagraphs[2];
}

// put second ad before the fourth heading if the article has at least four headings
$splitOnHeadings = preg_split('/(<h[23])/', $textBetweenAds, 5, PREG_SPLIT_DELIM_CAPTURE);
// checking for nine because if we split on four headings there will be one element for each
// heading delimeter plus one for each of the surrounding substrings
if(count($splitOnHeadings) == 9)
{
   $textBetweenAds = $splitOnHeadings[0] . $splitOnHeadings[1] . $splitOnHeadings[2] . $splitOnHeadings[3] . $splitOnHeadings[4] . $splitOnHeadings[5] . $splitOnHeadings[6];
   $textAfterAds = $splitOnHeadings[7] . $splitOnHeadings[8];
}

?>
<?php echo $textBeforeAds ?>
<div class="ad-custom">
   <script type="text/javascript">
      var google_ad_channel = '<?php echo $channel->getSetting('ad_article_1') ?>';
      var google_ad_client = 'pub-3619764495662405';
      var google_ad_output = 'js';
      var google_ad_type = '<?php echo $channel->getSetting('ad_article_type') ?>';
      var google_image_size = '<?php echo $channel->getSetting('ad_article_size') ?>';
      var google_max_num_ads = '3';
   </script>
   <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>
<?php echo $textBetweenAds ?>
<div class="ad-custom">
   <script type="text/javascript">
      var google_ad_channel = '<?php echo $channel->getSetting('ad_article_2') ?>';
      var google_ad_client = 'pub-3619764495662405';
      var google_ad_output = 'js';
      var google_ad_type = '<?php echo $channel->getSetting('ad_article_type') ?>';
      var google_image_size = '<?php echo $channel->getSetting('ad_article_size') ?>';
      var google_max_num_ads = '3';
      var google_skip = '3';
   </script>
   <script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>
<?php echo $textAfterAds ?>
<?php
$chitika = $channel->getSetting('ad_chitika');

if(!empty($chitika)):
?>
<div class="ads-bottom">
   <script type="text/javascript">
      aj_server = 'http://rotator.adjuggler.com/servlet/ajrotator/'; aj_tagver = '1.0';
      aj_zone = 'ltk'; aj_adspot = '<?php echo $chitika ?>'; aj_page = '0'; aj_dim ='755219'; aj_ch = ''; aj_ct = ''; aj_kw = '';
      aj_pv = true; aj_click = '';
   </script>
   <script type="text/javascript" src="http://img1.adjuggler.com/banners/ajtg.js"></script>
</div>
<?php else: ?>
<div class="ads-bottom">
   <script type="text/javascript">
      aj_server = 'http://rotator.adjuggler.com/servlet/ajrotator/'; aj_tagver = '1.0';
      aj_zone = 'ltk'; aj_adspot = '<?php echo $channel->getSetting('ad_3') ?>'; aj_page = '0'; aj_dim ='286708'; aj_ch = ''; aj_ct = ''; aj_kw = '';
      aj_pv = true; aj_click = '';
   </script>
   <script type="text/javascript" src="http://img1.adjuggler.com/banners/ajtg.js"></script>
</div>
<?php endif; ?>

<?php if ($relatedArticles = $article->getRelatedArticles()): ?>
   <div id="bottomarticleheading">
      <div id="articlesHeading">
         <strong>Related Articles</strong>
      </div>
      <ul>
         <?php foreach ($relatedArticles as $relatedArticle): ?>
            <li><a href="/<?php echo $relatedArticle['url'] ?>"><?php echo $relatedArticle['title'] ?></a>
         <?php endforeach; ?>
      </ul>
   </div>
<?php endif; ?>   
