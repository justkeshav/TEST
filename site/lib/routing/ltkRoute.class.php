<?php

/**
* ltkRoute implements the ltk_area requirement and adds the channel slug to the request parameters.
*
* The ltk_area requirement allows a route to require that only certain types of subdomains match them.
* This can be set to 'any' to essentially turn off the requirement. A setting of 'channel' requires
* the subdomain to be something other than 'www' and is the default. Anything else requires an exact
* match, e.g. a setting of 'www' would require the subdomain to be 'www' likewise a setting of 'sci-fi'
* would require the subdomain to be 'sci-fi'.
*
* Note: in the future we may want a 'referer' option to require the same subdomain as the prior request.
*
* Note: Whenever this class changes you need to also change ltkDoctrineRoute; check that class for additional details.
*/
class ltkRoute extends sfRequestRoute
{
   /**
   * Applies a default ltk_area requirement of 'channel'.
   *
   * @see sfRoute
   */
   public function __construct($pattern, $defaults = array(), $requirements = array(), $options = array())
   {
      if(!isset($requirements['ltk_area']))
      {
         $requirements['ltk_area'] = 'channel';
      }
      else
      {
         $requirements['ltk_area'] = strtolower($requirements['ltk_area']);
      }

      parent::__construct($pattern, $defaults, $requirements, $options);
   }

   /**
   * Returns true if the URL matches this route, false otherwise.
   *
   * @param  string  $url     The URL
   * @param  array   $context The context
   *
   * @return array   An array of parameters
   */
   public function matchesUrl($url, $context = array())
   {
      if(false === $parameters = parent::matchesUrl($url, $context))
      {
         return false;
      }

      $channel = $this->getChannel($context);

      // a subdomain is required
      if(empty($channel))
      {
         return false;
      }

      // enforce the ltk_area requirement
      if(!$this->matchesArea($this->requirements['ltk_area'], $channel))
      {
         return false;
      }

      $parameters = array_merge(array('channel' => $channel), $parameters);

      return $parameters;
   }

   /**
   * Returns true if the parameters match this route, false otherwise.
   *
   * @param  mixed   $params The parameters
   * @param  array   $context The context
   *
   * @return Boolean true if the parameters match this route, false otherwise.
   */
   public function matchesParameters($params, $context = array())
   {
      if(isset($params['ltk_area']))
      {
         // enforce the ltk_area requirement
         if(!$this->matchesArea($params['ltk_area'], $params['channel']))
         {
            return false;
         }

         unset($params['ltk_area']);
      }

      return parent::matchesParameters($params, $context);
   }

   /**
   * Generates a URL from the given parameters.
   *
   * @param  mixed   $params    The parameter values
   * @param  array   $context   The context
   * @param  Boolean $absolute  Whether to generate an absolute URL
   *
   * @return string The generated URL
   */
   public function generate($params, $context = array(), $absolute = false)
   {
      unset($params['ltk_area']);

      $params['channel'] = $this->getChannel($context);

      return parent::generate($params, $context, $absolute);
   }

   /**
   * Returns true if the subdomain meets the area requirement, false otherwise.
   *
   * @param string $area the ltk_area requirement setting
   * @param string $channel the subdomain to check
   *
   * @return boolean true if the subdomain meets the area requirement, false otherwise
   */
   protected function matchesArea($area, $channel)
   {
      return $area == 'any'
         || $area == $channel
         || ($area == 'channel' && $channel != 'www');
   }

   /**
   * Get the channel (subdomain) from the context
   *
   * @param  array   $context   The context
   *
   * @return string the channel (subdomain)
   */
   protected function getChannel(array $context)
   {
      $host = sfConfig::get('app_host');

      // return empty if the app host isn't found
      if(strpos($context['host'], $host) === false)
      {
         return '';
      }

      return str_replace('.', '', str_replace($host, '', $context['host']));
   }
   
   /**
   * Post-compiles a route.
   */
   protected function postCompile()
   {
      parent::postCompile();

      // add 'channel' to the list of vars so it is usable in matching and generation
      $this->variables['channel'] = $this->options['variable_prefixes'][0] . 'channel';
   }
}
