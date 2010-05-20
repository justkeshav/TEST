// anything that needs to run after the document has loaded needs to go in this function
$(function(){

// setup header vertical/channel menus
$('#header ul#menu > li').mouseenter(function(){
   $(this).addClass('active');
   $(this).children('ul').slideDown();
}).mouseleave(function(){
   $(this).children('ul').slideUp();
   $(this).removeClass('active');
});

// setup slideshow links to open in a new window
$('a[rel*=slideshow]').live('click', function(event){
   // only if left-clicked
   if((!$.browser.msie && event.button == 0) || ($.browser.msie && event.button == 1))
   {
      event.preventDefault();
      window.open($(this).attr('href'), 'slideshow', 'width=950,height=606');
   }
});

// setup quiz links to open in a new window
$('a[rel*=quiz]').live('click', function(event){
   // only if left-clicked
   if((!$.browser.msie && event.button == 0) || ($.browser.msie && event.button == 1))
   {
      event.preventDefault();
      window.open($(this).attr('href'), 'slideshow', 'width=600,height=600');
   }
});

// compacts 'answer' form fields for Backend QuizContent form
var idx=0;
while ($('div.sf_admin_form_field_answer' + ++idx).length)
{
   $('div.sf_admin_form_field_answer' + idx).css('border', '0');
   $('input#quiz_content_answer' + idx).css('width', '600px');
   $('input#quiz_content_answer' + idx + '_response').css('width', '600px');
   if ($('input#quiz_content_type').val() == 1)
   {
      $('div.sf_admin_form_field_answer' + idx + '_response').css('float', 'left');
      $('div.sf_admin_form_field_answer' + idx + '_valid').css('float', 'right');
      $('div.sf_admin_form_field_answer' + idx + '_valid').css('margin-top', '-40px');
   }
   else
   {
      $('div.sf_admin_form_field_answer' + idx + '_response').css('display', 'none');
      $('div.sf_admin_form_field_answer' + idx + '_valid').css('display', 'none');
   }
}

// set widget visbility defaults for Backend User form
if ($('select#sf_guard_user_groups_list').length)
{
	toggleRoleWidgets();
}

// open article preview in a new window
$("#article_preview").click(function()
{
   window.open($(this).attr('href'), 'articlepreview', 'width=800,height=600');
   return false;
});

});



