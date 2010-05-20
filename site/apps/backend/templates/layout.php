<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>

<?php if($sf_user->isAuthenticated()): ?>
   <div id="menu">
      <?php if($sf_user->isSuperAdmin() || $sf_user->getGuardUser()->isGroupEditor()): ?>
         <?php echo link_to('Users', '@sf_guard_user') ?> | 
         <?php echo link_to('Verticals','@vertical') ?> |
      <?php endif; ?>
      <?php echo link_to('Titles', '@title') ?> |      
      <?php echo link_to('Logout', '@sf_guard_signout') ?>      
   </div>
<?php endif; ?>

    <?php echo $sf_content ?>

  </body>
</html>
