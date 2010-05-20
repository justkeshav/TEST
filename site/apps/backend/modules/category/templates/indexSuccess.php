<?php
/**
* This is copied from the auto-generated version just to change the h1 text. If you change
* generators.yml the auto-generated version of this file should be checked for updates.
*/
?>
<?php use_helper('I18N', 'Date') ?>
<?php include_partial('category/assets') ?>

<div id="sf_admin_container">
  <?php if(isset($channel)): ?>
     <div class="crumbs"><?php echo
         link_to($channel->Vertical, "@channel?vertical_id={$channel->Vertical->id}"), " &raquo; ",
         link_to($channel, "@channel_edit?id={$channel->id}"), " &raquo; "
     ?></div>
  <?php endif; ?>
  <h1><?php echo __('Categories', array(), 'messages') ?></h1>

  <?php include_partial('category/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('category/list_header', array('pager' => $pager)) ?>
  </div>


  <div id="sf_admin_content">
    <form action="<?php echo url_for('category_collection', array('action' => 'batch')) ?>" method="post">
    <?php include_partial('category/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
    <ul class="sf_admin_actions">
      <?php include_partial('category/list_batch_actions', array('helper' => $helper)) ?>
      <?php include_partial('category/list_actions', array('helper' => $helper)) ?>
    </ul>
    </form>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('category/list_footer', array('pager' => $pager)) ?>
  </div>
</div>
