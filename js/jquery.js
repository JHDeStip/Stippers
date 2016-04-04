	$(document).ready(function(){	
	
	// NAVIGATIE
	$("header").prepend('<div id="menu-icon"></div>');
	
	$(".menu").on("click", function(){
    $('body').toggleClass('mobile');
		
	});
});


function checkMobile() {
    if ($(window).width() < 900)
        $('html').addClass('mobile');
    else
        $('html').removeClass('mobile');
}
