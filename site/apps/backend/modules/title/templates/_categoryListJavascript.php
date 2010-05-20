<script type="text/javascript">
// repopulate categories list when channel id changes on a title form
function changeCategoriesList()
{ 
   $('#title_categories_list').html('');
   var categoryList = 
   $.ajax(
      {
         url: '<?php echo url_for("title/categoryList/"); ?>'+'?channel_id='+$("#title_channel_id").val(),
         async: false
      }
   ).responseText;
   $('#unassociated_title_categories_list').html(categoryList);    
}
</script>