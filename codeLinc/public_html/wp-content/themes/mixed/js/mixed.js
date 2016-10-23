jQuery(function($){

	$('.page-loader').delay(700).fadeOut();
    $('.page-loader').find('img').fadeOut();

	$('.slider').fractionSlider({
		'fullWidth': 			true,
		'controls': 			true, 
		'pager': 				true,
		'responsive': 			true,
		'dimensions': 			"950,400",
	    'increase': 			false,
		'pauseOnHover': 		false,
		'slideEndAnimation': 	true,
		'speedIn' : 1000,
		'timeout' : 5000,
		'speedOut' : 1000
		
	});


	$('.entry-featured-image').hover(function(){

		$(this).find('.overlay').stop().fadeToggle(150);

		$(this).find('img').stop().toggleClass('img-transform');

	});	


	if($(window).width() > 769){

		$('.nav ul').find('a').hover(function(){

		$(this).stop().animate({left:'10px'},300,function(){			

		})

		},function(){

			$(this).stop().animate({left:0},300);			

		});


		$('.nav li').hover(function(){

			$(this).find('ul').stop().fadeToggle();

		});

		$('.nav li').find('ul').hover(function(){

			$(this).siblings('a').toggleClass('nav-toggle');

		});

	}

	$('.nav-touch').click(function(){

		$(this).toggleClass('nav-touch-toggle');

		$('.nav').slideToggle(150);

	});

	if($(window).width() < 769 ){

		$('.nav').find('a').click(function(){

			$('.nav').slideUp(150);

		});

	}

	$('article').fitVids();

	$('.search-submit').click(function(){

		$('.searchform').submit();

	});



});