<?php

$first = true;

foreach($image->sizes as $sizeName => $size)
{
   if(!$first)
   {
      echo '<br />';
   }
   else
   {
      $first = false;
   }

   $url = $image->genUrl($sizeName);

   echo "<a href=\"$url\" target=\"_blank\">$url</a> ({$size['w']} x {$size['h']})";
}
