
<div class="ads-top">
   <script type="text/javascript">
      aj_server = 'http://rotator.adjuggler.com/servlet/ajrotator/'; aj_tagver = '1.0';
      aj_zone = 'ltk'; aj_adspot = '<?php echo $channel->getSetting('ad_1') ?>'; aj_page = '0'; aj_dim ='286708'; aj_ch = ''; aj_ct = ''; aj_kw = '';
      aj_pv = true; aj_click = '';
   </script>
   <script type="text/javascript" src="http://img1.adjuggler.com/banners/ajtg.js"></script>
   <script type="text/javascript">
      aj_server = 'http://rotator.adjuggler.com/servlet/ajrotator/'; aj_tagver = '1.0';
      aj_zone = 'ltk'; aj_adspot = '<?php echo $channel->getSetting('ad_2') ?>'; aj_page = '0'; aj_dim ='286708'; aj_ch = ''; aj_ct = ''; aj_kw = '';
      aj_pv = true; aj_click = '';
   </script>
   <script type="text/javascript" src="http://img1.adjuggler.com/banners/ajtg.js"></script>
</div>

<?php

echo '<div class="articles">';
include_content_split_list($category->Articles);
echo '</div>';

$slideshows = $category->Slideshows;
if(!empty($slideshows))
{
   echo '<div class="slideshows"><h2>Slideshows</h2>';
   include_content_split_list($slideshows, array('rel' => 'slideshow'));
   echo '</div>';
}

$quizzes = $category->Quizzes;
if(!empty($slideshows))
{
   echo '<div class="quizzes"><h2>Quizzes</h2>';
   include_content_split_list($quizzes, array('rel' => 'quiz'));
   echo '</div>';
}

$subcategories = $category->getNode()->getChildren();
if(!empty($subcategories))
{
   echo '<div class="subcats"><h2>Subcategories</h2>';
   include_content_split_list($subcategories);
   echo '</div>';
}

// need to use getRaw to prevent output escaping of HTML content
echo $category->getPublishedArticle()->getRaw('text');

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
<?php endif;
