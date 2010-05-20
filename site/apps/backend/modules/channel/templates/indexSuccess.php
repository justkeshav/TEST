<?php
/**
* This is copied from the auto-generated version just to change the h1 text. If you change
* generators.yml the auto-generated version of this file should be checked for updates.
*/
?>
<?php use_helper('I18N', 'Date') ?>
<?php include_partial('channel/assets') ?>

<div id="sf_admin_container">
  <?php if(isset($vertical)): ?>
     <div class="crumbs"><?php echo "{$vertical} &raquo; " ?></div>
  <?php endif; ?>
  <h1><?php echo __('Channels', array(), 'messages') ?></h1>

  <?php include_partial('channel/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('channel/list_header', array('pager' => $pager)) ?>
  </div>


  <div id="sf_admin_content">
    <?php include_partial('channel/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
    <ul class="sf_admin_actions">
      <?php include_partial('channel/list_batch_actions', array('helper' => $helper)) ?>
      <?php include_partial('channel/list_actions', array('helper' => $helper)) ?>
    </ul>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('channel/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
