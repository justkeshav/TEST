
<?php use_helper('I18N', 'Date') ?>
<?php include_partial('title/assets') ?>

<div id="sf_admin_container">
   <h1><?php echo __('Title Activation', array(), 'messages') ?></h1>

   <?php include_partial('title/flashes') ?>
   
   <form action="<?php echo url_for('@title').'/activate'; ?>" method="POST">
      <?php echo $form ?>
      <input type="submit" value="Submit" />
   </form>
   
</div>


