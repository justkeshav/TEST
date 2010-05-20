<?php
/**
* This is the common site sidebar that is displayed on every page.
*/
?>
<div id="sidebar">
   <?php include_component('category', 'sidebar') ?>
   <div class="section ad">
      <script type="text/javascript">
         aj_server = 'http://rotator.adjuggler.com/servlet/ajrotator/'; aj_tagver = '1.0';
         aj_zone = 'ltk'; aj_adspot = '<?php echo $channel->getSetting('ad_sidebar') ?>'; aj_page = '0'; aj_dim ='286708'; aj_ch = ''; aj_ct = ''; aj_kw = '';
         aj_pv = true; aj_click = '';
      </script>
      <script type="text/javascript" src="http://img1.adjuggler.com/banners/ajtg.js"></script>
   </div>
   <div class="section tools">
      <div class="hdg">LoveToKnow Tools</div>
      <ul>
         <li><a onclick="pageTracker._trackPageview('/external/twitter');" href="http://twitter.com/lovetoknow" rel="nofollow,external"><img alt="Follow ltk on twitter" title="Follow ltk on twitter" src="/images/icon-twitter.gif"> Follow ltk on twitter</a></li>
         <li><a onclick="pageTracker._trackPageview('/external/facebook/page');" href="http://www.facebook.com/pages/LoveToKnow/43383149747" rel="nofollow,external"><img alt="Visit us on facebook" title="Follow ltk on facebook" src="/images/icon-facebook.gif"> Visit us on facebook</a></li>
         <li><a onclick="pageTracker._trackPageview('/external/facebook/share');" name="fb_share" type="button_count" href="http://www.facebook.com/sharer.php" rel="nofollow"><span style="font-size:12px;font-weight:normal;font-family:arial !important;">Share on facebook</span></a></li>
      </ul>
   </div>
</div>
