<?php use_helper('I18N', 'Date') ?>
<?php include_partial('FeatureArticle/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('FeatureArticle List', array(), 'messages') ?></h1>

  <?php include_partial('FeatureArticle/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('FeatureArticle/list_header', array('pager' => $pager)) ?>
  </div>

  <div id="sf_admin_bar">
    <?php include_partial('FeatureArticle/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <form action="<?php echo url_for('feature_article_collection', array('action' => 'batch')) ?>" method="post">
    <?php include_partial('FeatureArticle/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
    <ul class="sf_admin_actions">
      <?php include_partial('FeatureArticle/list_batch_actions', array('helper' => $helper)) ?>
      
      <?php      
      echo '<a href="FeatureArticle/new?channel_id='.$_REQUEST['channel_id'].'" /> New </a>';
      ?>
    </ul>
    </form>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('FeatureArticle/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
