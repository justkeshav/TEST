<?php use_helper('I18N', 'Date') ?>
<?php include_partial('FeatureArticle/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('New FeatureArticle', array(), 'messages') ?></h1>

  <?php include_partial('FeatureArticle/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('FeatureArticle/form_header', array('feature_article' => $feature_article, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('FeatureArticle/form', array('feature_article' => $feature_article, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('FeatureArticle/form_footer', array('feature_article' => $feature_article, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
