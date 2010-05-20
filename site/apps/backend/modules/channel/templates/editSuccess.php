<?php
/**
* This is copied from the auto-generated version just to change the h1 text. If you change
* generators.yml the auto-generated version of this file should be checked for updates.
*/
?>
<?php use_helper('I18N', 'Date') ?>
<?php include_partial('channel/assets') ?>

<div id="sf_admin_container">
  <div class="crumbs"><?php echo link_to($channel->Vertical, "@channel?vertical_id={$channel->Vertical->id}"), " &raquo; " ?></div>
  <h1><?php echo __('%%title%%', array('%%title%%' => $channel->getTitle()), 'messages') ?></h1>

  <?php include_partial('channel/flashes') ?>

  <div id="sf_admin_header">
    <?php include_partial('channel/form_header', array('channel' => $channel, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>

  <div id="sf_admin_content">
    <?php include_partial('channel/form', array('channel' => $channel, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>

  <div id="sf_admin_footer">
    <?php include_partial('channel/form_footer', array('channel' => $channel, 'form' => $form, 'configuration' => $configuration)) ?>
  </div>
</div>
