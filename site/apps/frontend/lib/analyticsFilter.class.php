<?php

class analyticsFilter extends sfFilter
{
   public function execute($filterChain)
   {
      // Execute next filter
      $filterChain->execute();

      $channel = sfContext::getInstance()->get('channel');

      $script = <<<SCRIPT
<script type="text/javascript">
   var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
   document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
   try {
      var pageTracker = _gat._getTracker("{$channel->getSetting('analytics_code')}");
      pageTracker._trackPageview();
   } catch(err) {}
</script>
<script type="text/javascript">
   document.write(unescape("%3Cscript src='" + (document.location.protocol == "https:" ? "https://sb" : "http://b") + ".scorecardresearch.com/beacon.js' %3E%3C/script%3E"));
</script>
<script type="text/javascript">
   COMSCORE.beacon({c1:2, c2:6036441, c3:"", c4:"", c5:"", c6:"", c15:""});
</script>
<noscript><img src="http://b.scorecardresearch.com/p?c1=2&c2=6036441&c3=&c4=&c5=&c6=&c15=&cj=1" /></noscript>
SCRIPT;

      if(!empty($script))
      {
         $response = $this->context->getResponse();

         $content = $response->getContent();
         $bodyPosition = strpos($content, '</body>');
         $hasBody = ($bodyPosition !== false);

         if($hasBody)
         {
            $content = substr($content, 0, $bodyPosition) . $script . substr($content, $bodyPosition);
         }

         $response->setContent($content);
      }
   }
}

