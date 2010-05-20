// toggle visibility of Role related widgets
function toggleRoleWidgets()
{
   var admin;
   var role = $('select#sf_guard_user_groups_list').val();
   if ($('input#sf_guard_user_is_super_admin').is(':checked'))
   {
      role = 1;
      admin = true;
   }

   $('select#sf_guard_user_permissions_list option[value$="6"]').css('display','none');

   if (role == 1)
   {
      if (admin)
      {
	     // admins are always Group Editors
	     $('select#sf_guard_user_groups_list').val('1');
         $('select#sf_guard_user_permissions_list').val('1');
		 $('div.sf_admin_form_field_groups_list').css('display','none');
         $('fieldset#sf_fieldset_permissions').css('display','none');	
	  }
	  else
	  {
         $('fieldset#sf_fieldset_permissions').css('display','block');	
		 $('div.sf_admin_form_field_groups_list').css('display','block');		
         $('label[for$="sf_guard_user_permissions_list"]').text('Editor Level');		
	     $('select#sf_guard_user_permissions_list option[value$="1"]').css('display','block'); 
	     $('select#sf_guard_user_permissions_list option[value$="2"]').css('display','block');
	     $('select#sf_guard_user_permissions_list option[value$="3"]').css('display','none');
	     $('select#sf_guard_user_permissions_list option[value$="4"]').css('display','none');
	     $('select#sf_guard_user_permissions_list option[value$="5"]').css('display','none');
	     if ($('select#sf_guard_user_permissions_list').val() > 2)
	     {
	        $('select#sf_guard_user_permissions_list').val('1');
	     }			
         $('div.sf_admin_form_field_editor_channels').css('display', 'block'); 
         $('div.sf_admin_form_field_writer_channels').css('display', 'block');
      }
   }
   else if (role == 2)
   {
	  $('label[for$="sf_guard_user_permissions_list"]').text('Writer Level');
      $('fieldset#sf_fieldset_permissions').css('display','block');	
      $('div.sf_admin_form_field_groups_list').css('display','block');
      $('div.sf_admin_form_field_editor_channels').css('display', 'none');
      $('div.sf_admin_form_field_writer_channels').css('display', 'block');
      $('select#sf_guard_user_permissions_list option[value$="1"]').css('display','none'); 
      $('select#sf_guard_user_permissions_list option[value$="2"]').css('display','none');
      $('select#sf_guard_user_permissions_list option[value$="3"]').css('display','block');
      $('select#sf_guard_user_permissions_list option[value$="4"]').css('display','block');
      $('select#sf_guard_user_permissions_list option[value$="5"]').css('display','block');
      if ($('select#sf_guard_user_permissions_list').val() < 3)
      {
         $('select#sf_guard_user_permissions_list').val('3');
      }
   }
   else if (role == 3)
   {
      $('fieldset#sf_fieldset_permissions').css('display','none'); 
   }
}
