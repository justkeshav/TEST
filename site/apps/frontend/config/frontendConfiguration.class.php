<?php

class frontendConfiguration extends sfApplicationConfiguration
{
   public function configure()
   {
      $this->dispatcher->connect('template.filter_parameters', array($this, 'templateFilterParameters'));
   }

   public function templateFilterParameters(sfEvent $event, $parameters)
   {
      $slug = sfContext::getInstance()->getRequest()->getParameter('channel');
      $channel = Doctrine::getTable('Channel')->findOneBySlug($slug);

      if(!($channel instanceof Channel))
      {
         throw new Exception("Unknown channel '$slug'");
      }

      // make current channel available via context
      sfContext::getInstance()->set('channel', $channel);

      // make current $channel var available to all templates
      $parameters['channel'] = $channel;

      return $parameters;
   }
}
