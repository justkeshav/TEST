// Hide Categories list selector when type changed to 'category'
function toggleCategoriesList()
{
   if ($('#title_type').attr('value') == 'Category')
   {
      $('.sf_admin_form_field_categories_list').css('display','none');
   }
   else
   {
      $('.sf_admin_form_field_categories_list').css('display','block');	    
   } 
}


