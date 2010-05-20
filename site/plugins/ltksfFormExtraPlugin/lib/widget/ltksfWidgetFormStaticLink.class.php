<?php

/**
* Renders a URL value as a static link to that URL.
*/
class ltksfWidgetFormStaticLink extends ltksfWidgetFormStatic
{
   /**
   * @param  string $name        The element name
   * @param  string $value       The value displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
   public function render($name, $value = null, $attributes = array(), $errors = array())
   {
      return $this->renderContentTag('a', self::escapeOnce($value), array_merge(array('name' => $name, 'href' => self::escapeOnce($value), 'target' => '_blank'), $attributes));
   }
}
