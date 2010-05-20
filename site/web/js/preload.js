// quick function to write to the document
// NOTE: this function should *always* be used instead of document.write() or document.writeln()
function w(s)
{
   document.write(s);
}

function google_ad_request_done(google_ads)
{
   if (google_ads.length == 0)
   {
      return;
   }

   var s = '<p class="heading"><a href=\"' + google_info.feedback_url + '\" target=\"_blank\">Ads by Google</a></p>';

   switch(google_ads[0].type)
   {
      case 'flash':
         s += '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="'
            + google_ad.image_width + '" height="' + google_ad.image_height + '"> <param name="movie" value="'
            + google_ad.image_url + '"><param name="quality" value="high"><param name="AllowScriptAccess" value="never"><embed src="'
            + google_ad.image_url + '" width="' + google_ad.image_width + '" height="' + google_ad.image_height
            + '" type="application/x-shockwave-flash" AllowScriptAccess="never" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed></object>';
         break;

      case 'image':
         s += '<a href="' + google_ads[0].url + '" target="_blank" title="go to ' + google_ads[0].visible_url
            + '" onmouseout="window.status=\'\'" onmouseover="window.status=\'go to ' + google_ads[0].visible_url + '\';return true"><img border="0" src="'
            + google_ads[0].image_url + '"width="' + google_ads[0].image_width + '"height="' + google_ads[0].image_height + '"></a>';
         break;

      case 'html':
         s += google_ads[0].snippet;
         break;

      default:
         for(var i = 0; i < google_ads.length; ++i)
         {
            s += '<p class="ad"><a class="headline" href="' + google_ads[i].url + '" onmouseout="window.status=\'\'" onmouseover="window.status=\'go to '
               + google_ads[i].visible_url + '\';return true">' + google_ads[i].line1 + '</a> - ' + google_ads[i].line2 + ' ' + google_ads[i].line3
               + ' <a class="url" href="' + google_ads[i].url + '" onmouseout="window.status=\'\'" onmouseover="window.status=\'go to '
               + google_ads[i].visible_url + '\';return true">' + google_ads[i].visible_url + '</a></p>';
         }
         break;
   }

   w(s);

   return;
}
