<?php

/**
* Displays the value as static text. Any HTML in the value is escaped.
*/
class ltksfWidgetFormStaticPartial extends ltksfWidgetFormStatic
{
   /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * templateName: The name of the partial to render (required)
   *  * vars:         An array of vars you want passed to the partial
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   */
   protected function configure($options = array(), $attributes = array())
   {
      $this->addRequiredOption('templateName');
      $this->addOption('vars', array());

      parent::configure($options, $attributes);
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
      return get_partial($this->getOption('templateName'), $this->getOption('vars'));
   }
}
