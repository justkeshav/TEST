<?php
/**
* This is the common site header that is displayed on every page.
*/
?>
<div class="section categories">
   <div class="hdg"><?php echo $channel->short_title ?> Categories</div>
   <ul>
      <?php foreach($categories as $category): ?>
         <li><?php echo link_to_content($category) ?></li>
      <?php endforeach ?>
   </ul>
</div>
