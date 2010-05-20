<?php

use_stylesheet('slideshow');
use_javascript('slideshow');

decorate_with(false);

$maxRank = count($slide->Slideshow->Slides) - 1;

$prevLabel = '&#x25c4; Previous';
$prevRank = '&page=' . ($slide->rank - 1);
$prevDisabled = false;

$nextLabel = 'Next &#x25ba;';
$nextRank = '&page=' . ($slide->rank + 1);

$pauseLabel = 'Start';

if($slide->rank == 0)
{
   $prevLabel = 'Last';
   $prevRank = '&page=' . $maxRank;
}
else if($slide->rank == $maxRank)
{
   $nextLabel = 'Replay';
   $nextRank = '';
   $pauseLabel = 'Pause';
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
   <?php include_http_metas() ?>
   <?php include_metas() ?>
   <?php include_title() ?>
   <link rel="shortcut icon" href="/favicon.ico" />
   <?php include_stylesheets() ?>
</head>

<body class="slideshow <?php echo $channel->Vertical->abbv ?>">

<h1><?php echo $slide->Slideshow->title ?></h1>

<div id="content">
   <h2><?php echo $slide->heading ?></h2>
   <?php echo $slide->getRaw('text') ?>
</div>

<div id="image">
   <?php if(!empty($slide->image_link)): ?><a href="<?php echo $slide->image_link ?>" rel="external,nofollow"><?php endif; ?>
   <img src="<?php echo $slide->Image->genUrl('slide') ?>" />
   <?php if(!empty($slide->image_link)): ?></a><?php endif; ?>
</div>

<div id="buttons">
   <button id="close">Close</button>
   <?php echo link_to($prevLabel, "@content?url={$slide->Slideshow->url}$prevRank") ?>
   <button id="pause"><?php echo $pauseLabel ?></button>
   <?php echo link_to($nextLabel, "@content?url={$slide->Slideshow->url}$nextRank", array('id' => 'next')) ?>
</div>

<div id="ad">
   <script type="text/javascript">
      aj_server = 'http://rotator.adjuggler.com/servlet/ajrotator/'; aj_tagver = '1.0';
      aj_zone = 'ltk'; aj_adspot = '<?php echo $channel->getSetting('ad_slide') ?>'; aj_page = '0'; aj_dim ='286700'; aj_ch = ''; aj_ct = ''; aj_kw = '';
      aj_pv = true; aj_click = '';
   </script>
   <script type="text/javascript" src="http://img1.adjuggler.com/banners/ajtg.js"></script>
</div>

<?php include_javascripts() ?>

</body>

</html>
