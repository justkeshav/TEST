<?php

/**
* ltkFullUrlDoctrineRoute is for routes that need to match the entire URL
*/
class ltkFullUrlDoctrineRoute extends ltkDoctrineRoute
{
   protected function initializeOptions()
   {
      // ascii 0 is NULL -- we dont want to match any separators, but it requires an ascii char, so this was next best option after empty array
      $this->defaultOptions['segment_separators'] = array(chr(0));

      // we dont want anything added onto the URLs by default
      $this->defaultOptions['extra_parameters_as_query_string'] = false;

      parent::initializeOptions();

      // HACK: allows segment_separators to not include a forward slash
      $this->options['segment_separators_regex'] = str_replace('(?:', '(?:^/|', $this->options['segment_separators_regex']);
   }
}
