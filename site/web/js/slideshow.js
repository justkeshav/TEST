var slideshowTimer = null;

function enableSlideshow()
{
   $('#pause').text('Pause');
   slideshowTimer = setTimeout("goNextSlide()", 10000);
}

function disableSlideshow()
{
   clearTimeout(slideshowTimer);
   $('#pause').text('Start');
}

function toggleSlideshow()
{
   if($('#pause').text() == 'Pause')
   {
      disableSlideshow();
   }
   else
   {
      enableSlideshow();
   }
}

function goNextSlide()
{
   window.location = $('#next').attr('href');
}

// anything that needs to run after the document has loaded needs to go in this function
$(function(){

$('#close').click(function(){
   window.close();
});

$('#pause').click(function(){
   toggleSlideshow();
});

toggleSlideshow();

});
