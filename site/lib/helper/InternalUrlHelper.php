<?php
/**
* Helper functions for generating internal urls
*/

/**
* Creates an HTML <a> element that links to the site's home page with an absolute URL
*
* @param string $label text label or HTML to be contained within the <a> element
* @param string $path absolute path to the resource on the www site (starting with a forward-slash)
* @param array $options additional XHTML compliant <a> element parameters
* @return string XHTML compliant <a> element
* @see link_to
*/
function link_to_www($label, $path, $options = array())
{
   $host = sfConfig::get('app_host');
   return link_to($label, "http://www.$host$path", $options);
}

/**
* Creates an HTML <a> element that links to the site's home page with an absolute URL
*
* @param string $label text label or HTML to be contained within the <a> element
* @param array $options additional XHTML compliant <a> element parameters
* @return string XHTML compliant <a> element
* @see link_to_www
*/
function link_to_home($label, $options = array())
{
   return link_to_www($label, "/", $options);
}

/**
* Creates an HTML <a> element that links to a vertical page with an absolute URL
*
* @param Vertical $vertical the vertical to create a link for
* @param string $label text label or HTML to be contained within the <a> element; will use the vertical title if not set
* @param array $options additional XHTML compliant <a> element parameters
* @return string XHTML compliant <a> element
* @see link_to_www
*/
function link_to_vertical($vertical, $label = null, $options = array())
{
   if(empty($label))
   {
      $label = $vertical;
   }

   return link_to_www($label, "{$vertical->url}", $options);
}

/**
* Creates an HTML <a> element that links to a channel's home page with an absolute URL
*
* @param Channel $channel the channel to create a link for
* @param string $label text label or HTML to be contained within the <a> element; will use the channel title if not set
* @param array $options additional XHTML compliant <a> element parameters
* @return string XHTML compliant <a> element
* @see link_to
*/
function link_to_channel($channel, $label = null, $options = array())
{
   if(empty($label))
   {
      $label = $channel;
   }

   $host = sfConfig::get('app_host');

   return link_to($label, "http://{$channel->slug}.$host/", $options);
}

/**
* Creates an HTML <a> element that links to a content page with an absolute URL
*
* @param mixed $content the full URL content item to create a link for
* @param string $label text label or HTML to be contained within the <a> element; will use the content item's title if not set
* @param array $options additional XHTML compliant <a> element parameters
* @return string XHTML compliant <a> element
* @see link_to
*/
function link_to_content($content, $label = null, $options = array())
{
   $currentChannelSlug = sfContext::getInstance()->getRequest()->getParameter('channel');

   if(empty($label))
   {
      $label = $content->title;
   }

   if($currentChannelSlug != $content->Channel->slug || (array_key_exists('absolute', $options) && $options['absolute']))
   {
      $host = sfConfig::get('app_host');
      return link_to($label, "http://{$content->Channel->slug}.$host/{$content->url}", $options);
   }

   return link_to($label, 'content', $content);
}
function link_to_absolute($url, $label,$content)
{      
      $host = sfConfig::get('app_host');
      return link_to($content->$label, "http://{$content->Channel->slug}.$host/{$content->$url}");
}
