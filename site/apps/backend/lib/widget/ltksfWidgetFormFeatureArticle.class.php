<?php

/**
* Extends the dialog widget to specifically handle the Image model class with defaults for the most
* common settings.
*/
class ltksfWidgetFormFeatureArticle extends ltksfWidgetFormDialog
{
   /**
   * Configures the current widget.
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see ltksfWidgetFormDialog
   */
   protected function configure($options = array(), $attributes = array())
   {      
      $this->addRequiredOption('channel_id');
      $this->addOption('title');
      $this->addOption('browse_url');

      parent::configure($options, $attributes);

      if(!array_key_exists('dialog_internal_uri', $options))
      {
         $this->setOption('dialog_internal_uri', "@feature_browse?channel_id={$options['channel_id']}");
      }

      if(!array_key_exists('browse_url', $options))
      {
         $this->setOption('browse_url', "@feature_browse?channel_id={$options['channel_id']}");
      }

   }

   /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
   public function render($name, $value = null, $attributes = array(), $errors = array())
   {
      $dialog = parent::render($name, $value, $attributes, $errors);

      $browserUrl = $this->getOption('browse_url');

      if(empty($browserUrl))
      {
         return $dialog;
      }

      $input = $this->renderTag('input', array('type' => 'text', 'name' => "$name"));

      $script = sprintf('
<script type="text/javascript">

$(function(){

$("#%2$s").autocomplete({
   source: "%3$s",
   minLength: 1,
   focus: function(event, ui){
      $(this).val(ui.item.label);
      $("#%4$s img").attr("height", 35);
      $("#%4$s img").attr("src", ui.item.thumb);
      return false;
   },
   select: function(event, ui){
      $("#%1$s").val(ui.item.value);
      $("#%4$s img").removeAttr("height");
      $(this).focus();
      $(this).select();
      return false;
   }
});

});

</script>
         ',
         $this->generateId($name),
         $this->generateId("$name"),
         url_for($browserUrl),
         $this->generateId("preview_$name")
      );

      return "$input$script";
   }
}
