<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
   <?php include_http_metas() ?>
   <?php include_metas() ?>
   <?php include_title() ?>
   <link rel="shortcut icon" href="/favicon.ico" />
   <?php include_stylesheets() ?>
   <script type="text/javascript" src="/js/preload.js"></script>
</head>

<body class="<?php echo $channel->Vertical->abbv ?><?php if(has_slot('page-type-css-class')): ?> <?php include_slot('page-type-css-class'); endif; ?>">

<div id="page">

<div id="content">
   <h1><?php include_slot('heading') ?></h1>
   <?php   
     if (isset($_REQUEST['search']))
     {
         include_partial('common/search');
     }
     else
     {
         echo $sf_content;
     }
   ?>
</div>

<?php include_partial('common/sidebar') ?>

<?php include_component('common', 'header') ?>

<?php include_component('common','footer') ?>

</div>

<?php include_javascripts() ?>

</body>

</html>
