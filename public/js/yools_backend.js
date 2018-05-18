jQuery(document).ready(function( $ ) {


	/*
	    *
	    *
	    * FLOATING BUTTON
	    *
	    *
	*/

	setTimeout(function(){
	  $('.yools-floating-button .floating-message-1').addClass("active");
	}, 0);

	setTimeout(function(){
	  $('.yools-floating-button .floating-message').removeClass("active");
	}, 300000);

	setTimeout(function(){
	  $('.yools-floating-button .floating-message-2').addClass("active");
	}, 300250);

	$(".yools-floating-button").click(function(){
	    $(".yools-floating-button ul").toggle();
	});

});
