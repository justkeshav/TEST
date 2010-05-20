<?php if (!$form->getObject()->isNew()): ?>
   <div class="sf_admin_form_row">
      <div class="content">
         <?php if ($form->getObject()->Article->id): ?>
               <?php echo link_to('Edit the Category Description', "@article_edit?id={$form->getObject()->Article->id}") ?>
         <?php else: ?>
               <?php echo link_to('Add a Category Description', "@article_new?title_id={$form->getObject()->title_id}") ?>
         <?php endif; ?>
      </div>
   </div>
<?php endif; ?>