<?php
/**
* This is the common site header that is displayed on every page.
*/
?>
<div id="header">
   <div id="logo"><?php echo link_to_home('<img src="/images/logo.gif" alt="LoveToKnow" />') ?></div>
   <div id="tagline"><img src="/images/tagline.gif" alt="everything you'd love to know" /></div>
   <div id="date"><script type="text/javascript">w((new Date()).toLocaleDateString());</script></div>
   <ul id="menu">
      <li><?php echo link_to_home('Home') ?></li>
      <?php foreach($verticals as $vertical): ?>
         <li class="<?php echo $vertical->abbv ?>">
            <?php echo link_to_vertical($vertical) ?>
            <ul>
               <?php foreach($vertical->Channels as $channelForMenu): ?>
                  <li><?php echo link_to_channel($channelForMenu) ?></li>
               <?php endforeach ?>
            </ul>
         </li>
      <?php endforeach ?>
   </ul>
   <div id="section">
      <?php echo $channel->short_title ?>
       <div id="search">
           <form name="form" method="get" action="">
               <input type="text" name="q" />
               <input type="submit" name="search" value="Search" />
           </form>
       </div>
   </div>
   <ul id="crumbs">
      <li><?php echo link_to_home('LoveToKnow') ?> &raquo;</li>
      <li><?php echo link_to_vertical($channel->Vertical) ?> &raquo;</li>
      <li><?php echo link_to_channel($channel) ?> &raquo;</li>
      <?php include_slot('crumbs') ?>
      <li><?php include_slot('heading') ?></li>
   </ul>
</div>