<?php

/**
 * Renders a file upload widget that utilizes the Uploadify jQuery plugin.
 */
class ltksfWidgetFormUploadify extends sfWidgetFormInput
{
   /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * file_desc:  Description text displayed in the file type drop down of the open dialog (only used if file_types is specified)
   *  * file_types: An array of valid file types (extensions without a period)
   *
   * @see sfWidgetForm
   */
   public function configure($options = array(), $attributes = array())
   {
      parent::configure($options, $attributes);

      $this->addOption('file_desc', 'Files');
      $this->addOption('file_types');
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
      $input = parent::render($name, $value, $attributes, $errors);

      $extraConfig = '';

      $fileDesc = $this->getOption('file_desc');
      $fileTypes = $this->getOption('file_types');

      if(is_array($fileTypes) && count($fileTypes) > 0)
      {
         $extraConfig .= ", \"fileDesc\" : \"$fileDesc\"";
         $extraConfig .= ', "fileExt" : "*.' . implode(';*.', $fileTypes) . '"';
      }

      $script = sprintf('
<div id="%3$s" class="uploadify"></div>
<div id="%2$s"></div>
<script type="text/javascript">

$(function(){

$("#%3$s").uploadify({
   "uploader"    : "/ltksfFormExtraPlugin/swf/uploadify.swf",
   "script"      : "/ltksfFormExtraPlugin/uploadify.php",
   "checkScript" : "/ltksfFormExtraPlugin/uploadify-check.php",
   "cancelImg"   : "/ltksfFormExtraPlugin/images/cancel.png",
   "auto"        : true,
   "hideButton"  : true,
   "wmode"       : "transparent",
   "folder"      : "/uploads",
   "buttonText"  : "Browse...",
   "width"       : 79,
   "height"      : 20,
   "onInit"      : function(event){
      filePath = $("#%1$s").val();
      if($.inArray(filePath.split(".").pop().toLowerCase(), ["jpg", "jpeg", "gif", "png"]) > -1) $("#%2$s").html("<img width=\"100\" src=\"" + filePath + "\" />");
   },
   "onComplete"  : function(event, queueID, fileObj, response, data){
      $("#%1$s").val(fileObj.filePath);
      if($.inArray(fileObj.type.toLowerCase(), [".jpg", ".jpeg", ".gif", ".png"]) > -1) $("#%2$s").html("<img width=\"100\" src=\"" + fileObj.filePath + "\" />");
   }
   %4$s
});

});

</script>
         ',
         $this->generateId($name),
         $this->generateId("preview_$name"),
         $this->generateId("up_$name"),
         $extraConfig
      );

      return "$input$script";
   }
}
