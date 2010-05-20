<?php

/**
* Displays a date/time value as static text. Use the 'format' option to specify the output format.
*
* @see date()
*/
class ltksfWidgetFormStaticDateTime extends ltksfWidgetFormStatic
{
   /**
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormInput
   */
   protected function configure($options = array(), $attributes = array())
   {
      parent::configure($options, $attributes);
      $this->addOption('format', 'D, M jS, Y \a\t g:ia');
   }

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
      return $this->renderContentTag('span', date($this->getOption('format'), strtotime($value)), $attributes);
   }
}
