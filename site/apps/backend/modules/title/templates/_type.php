<?php echo $title->type ?>
<ul class="sf_admin_td_actions" style="display: inline; padding-left: 1em;">
<?php if ($title->status != Title::STATUS_QUEUED && $title->status != Title::STATUS_AVAILABLE): ?>
   <?php if (is_object($title->Content)): ?>   
      <li class="sf_admin_action_edit"><?php echo link_to('Edit', "@{$title->typeTableName}_edit?id={$title->Content->id}") ?></li>
   <?php else: ?>
      <li class="sf_admin_action_edit"><?php echo link_to('Edit', "@{$title->typeTableName}_new?title_id={$title->id}") ?></li>
   <?php endif; ?>
<?php endif; ?>