<?php

/**
* Represents a related object and allows for a new one to be created in a popup modal dialog.
* Use the available options to display a preview of the currently selected item.
*/
class ltksfWidgetFormDialog extends sfWidgetForm
{
   /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * dialog_internal_uri: The internal URI to get the form to display within the dialog (required)
   *  * button_label:        The label for the button that opens the dialog
   *  * dialog_title:        The text for the title bar of the dialog
   *  * dialog_submit_label: The label for the button that submits the dialog's form
   *  * dialog_width:        The minimum width for the dialog box
   *  * dialog_height:       The minimum height for the dialog box
   *  * preview:             HTML to display as the preview of the item when the form first loads
   *  * preview_js:          JavaScript that builds an HTML string for a preview of the item after the form
   *                         is submitted successfully. Use the response variable to access the json response
   *                         from the ajax form submission.
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   */
   protected function configure($options = array(), $attributes = array())
   {
      $this->addRequiredOption('dialog_internal_uri');
      $this->addOption('button_label', 'Choose...');
      $this->addOption('dialog_title', 'Choose');
      $this->addOption('dialog_submit_label', 'Submit');
      $this->addOption('dialog_width', 480);
      $this->addOption('dialog_height', 360);
      $this->addOption('preview');
      $this->addOption('preview_js');

      parent::configure($options, $attributes);
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
      $hidden = $this->renderTag('input', array('type' => 'hidden', 'name' => $name, 'value' => $value));

      $button = $this->renderContentTag(
         'button',
        '<span class="ui-button-text">' . $this->getOption('button_label') . '</span>',
         array_merge(array('name' => "dialog_link_$name", 'class' => 'ui-button ui-button-text-only ui-state-default ui-corner-all'), $attributes));

      $script = sprintf('
<script type="text/javascript">

$(document).ready(function(){

   $("#%1$s").parent().append($("<div>", { id: "%9$s", style: "vertical-align: top; display: inline;", html: \'%11$s\' }));
   %2$s = $("<div>", { id: "%2$s", title: "%4$s" }).appendTo("body");
   %2$s.dialog({
      bgiframe: true,
      autoOpen: false,
      width: %6$d,
      height: %7$d,
      modal: true,
      buttons: {
         Cancel: function() {
            %2$s.dialog("close");
         },
         "%8$s": function() {
            %2$s.find("form").submit();
         }
      },
      open: function() {
         %2$s.load("%5$s", null, function(){
            %2$s.find("form").validate({
               submitHandler: function(form){
                  $(form).ajaxSubmit({
                    success: function(response, status){
                       if(response.success)
                       {
                          $("#%1$s").val(response.value);
                          $("#%9$s").html(%10$s);
                          %2$s.dialog("close");
                       }
                       else
                       {
                          %2$s.html(response);
                       }
                    }
                  });
               }
            });
         });
      }
   });

   $("#%3$s").click(function(event) {
      event.preventDefault();
      %2$s.dialog("open");
   })

   $(".ui-button").hover(
      function(){
         $(this).addClass("ui-state-hover");
      },
      function(){
         $(this).removeClass("ui-state-hover");
      }
   ).mousedown(function(){
      $(this).addClass("ui-state-active");
   })
   .mouseup(function(){
      $(this).removeClass("ui-state-active");
   });

});

</script>
         ',
         $this->generateId($name),
         $this->generateId("dialog_$name"),
         $this->generateId("dialog_link_$name"),
         $this->getOption('dialog_title'),
         url_for($this->getOption('dialog_internal_uri')),
         $this->getOption('dialog_width'),
         $this->getOption('dialog_height'),
         $this->getOption('dialog_submit_label'),
         $this->generateId("preview_$name"),
         $this->getOption('preview_js'),
         $this->getOption('preview')
      );

      return "$hidden$button$script";
   }
}
