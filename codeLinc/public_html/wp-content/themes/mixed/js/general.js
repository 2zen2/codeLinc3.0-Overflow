jQuery(function($){

	$('a.rsswidget').parent('li').addClass('mixed-rss-li');

	
	if($(window).width() < 769){

		$('.header-cart-container').addClass('clearfix');

	}

	$('#whats-new-form').find('textarea').focus(function(){

		$(this).stop().animate({height:'150px'},500);

	});

	$('form#whats-new-form textarea').blur(function(){

		$(this).stop().animate({height:'60px'},500);

	});



});