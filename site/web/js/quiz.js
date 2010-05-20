// Toggle visibility of Properties fieldset for Quiz form
function toggleProperties()
{
   if ($('#sf_fieldset_properties').css('display') == 'none')
   {
      $('#properties').text('Hide Properties');
      $('#sf_fieldset_properties').css('display','block');
   }
   else
   {
      $('#properties').text('Show Properties');
      $('#sf_fieldset_properties').css('display','none');	    
   } 
}


