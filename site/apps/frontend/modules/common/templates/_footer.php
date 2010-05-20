<?php
/**
* This is the common site footer that is displayed on every page.
*/
?>
<div id="footer">
   <div class="links">
      <img src="/images/ltk.gif" alt="ltk" />      
      <?php echo link_to_channel($channel, $channel->getSetting('footer_text')) ?>
      | <?php echo link_to_www('Write For Us', '/write-for-us.htm') ?>
      | <?php echo link_to_www('Help', '/wiki-help.htm') ?>
      | <?php echo link_to_content($channel->Details->AboutChannel) ?>
      | <?php echo link_to_www('Privacy Policy', '/lovetoknow-privacy-policy.htm') ?>
      | <?php echo link_to_www('Editorial Policy', '/Editorial-Policy.html') ?>
      | <?php echo link_to_www('Terms of Service', '/disclaimers.htm') ?>
   </div>
   <div class="legal">&copy; 2006-<?php echo date('Y') ?> LoveToKnow Corp.</div>
</div>
