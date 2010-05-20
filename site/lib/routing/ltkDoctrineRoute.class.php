<?php

/**
* ltkDoctrineRoute implements the ltk_area requirement and adds the channel slug to the request parameters
* for Doctrine object routes
*
* Note: large portions of this class are duplicated from ltkRoute because this class needs to inherit sfDoctrineRoute
* instead of ltkRoute, so whenever ltkRoute changes this class must get the same changes. The copied areas are
* indicated with COPY and ENDCOPY comments
*/
class ltkDoctrineRoute extends sfDoctrineRoute
{
// COPY: from ltkRoute
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
// ENDCOPY

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
// COPY: from ltkRoute
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
// ENDCOPY

      // return false if the object cant be loaded
      if(!$this->getObjectForParameters($parameters))
      {
         return false;
      }

      return $parameters;
   }

// COPY: from ltkRoute
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
// ENDCOPY

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
// COPY: from ltkRoute
      unset($params['ltk_area']);

      $params['channel'] = $this->getChannel($context);
// ENDCOPY

      // we need URLs to be an exact match to the DB, so no URL entities
      // TODO: any security issues here?
      return urldecode(parent::generate($params, $context, $absolute));
   }

// COPY: from ltkRoute
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
// ENDCOPY

// COPY: from ltkRoute
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
// ENDCOPY

// COPY: from ltkRoute
   /**
   * Post-compiles a route.
   */
   protected function postCompile()
   {
      parent::postCompile();

      // add 'channel' to the list of vars so it is usable in matching and generation
      $this->variables['channel'] = $this->options['variable_prefixes'][0] . 'channel';
   }
// ENDCOPY
}
