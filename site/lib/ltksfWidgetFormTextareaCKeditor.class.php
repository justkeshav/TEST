<?php

/**
 * ltksfWidgetFormTextareaCKEditor represents a CKEditor HTML editor widget
 *
 * You must include the CKEditor JavaScript files
 *
 */
class ltksfWidgetFormTextareaCKEditor extends sfWidgetFormTextarea
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * config: The javascript configuration
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('config', '');
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The value selected in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $textarea = parent::render($name, $value, $attributes, $errors);

   $toolbar = "[ 
                  ['Format','-','PasteFromWord','-','Find','-','SpellChecker'],
                  ['Undo','Redo'], ['Bold','Italic'],['Indent','Outdent','-','NumberedList','BulletedList'],
                  ['Link','Unlink'],['ImageButton'],['Maximize'],['Source']
               ]";

    $js = '
<script type="text/javascript">
	window.onload = function()
	{
		CKEDITOR.replace( "'.$name.'", 
         {
            toolbar : '.$toolbar.',
            skin : "office2003",
            language : "en",
            forcePasteAsPlainText : true,
            linkShowAdvancedTab : false,
            linkShowTargetTab : false,
            width : "75%",
            height : 400              
         });
	};
</script>';

    return $textarea.$js;

   }
}
