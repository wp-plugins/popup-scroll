$(function(){
	
});


$(window).on('load resize', function() {
	$('.small-col figure').css('height', $('.large-col').outerHeight());
});