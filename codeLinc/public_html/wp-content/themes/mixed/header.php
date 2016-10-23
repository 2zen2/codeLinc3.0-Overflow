<!DOCTYPE html>
<!--[if IE 8]> <html <?php language_attributes(); ?> class="ie8"> <![endif]-->
<!--[if !IE]><!--> <html <?php language_attributes(); ?>> <!--<![endif]-->

<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<title><?php wp_title( '|', true, 'right' ); ?></title>	
	
	
	<!-- Mobile Specific Meta -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	
	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->

<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<div class="page-loader">
	<img src="<?php echo get_template_directory_uri(); ?>/img/loader.gif" alt="<?php _e('ajax loader','business'); ?>">
</div> <!-- end page-loader -->


<?php global $mixed,$woocommerce; ?>

<header class="top-header" role="banner">
			
	<div class="container">
				
		<div class="col-sm-3">

			<?php if(isset($mixed['logo']['url'])){ ?>

					<?php if($mixed['logo']['url']){ ?>
						<figure class="logo">
							<a href="<?php echo esc_url(home_url('/')); ?>" class="u-url" title="logo" rel="home">
								<img src="<?php echo $mixed['logo']['url']; ?>" alt="<?php bloginfo('description'); ?>">
							</a>
						</figure>
					<?php }else{ ?>
					
					<h4 class="p-title">
						<a href="<?php echo esc_url(home_url('/')); ?>" class="u-url" title="<?php bloginfo('description'); ?>" rel="home"><?php bloginfo('name'); ?></a>
					</h4>

					<?php } }else{ ?>

						<h4 class="p-title">
							<a href="<?php echo esc_url(home_url('/')); ?>" class="u-url" title="<?php bloginfo('description'); ?>" rel="home"><?php bloginfo('name'); ?></a>
						</h4>

			<?php } ?>			
			

			<i class="fa fa-align-justify nav-touch"></i>

		</div> <!-- end col-sm-3 -->

		<div class="col-sm-9">

			<?php if(function_exists('is_woocommerce')){ ?>

					<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" class="mobile-cart-content">						
						.
						<div class="mobile-content">
							<div class="cart-content-count">
							<?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'mixed'),  $woocommerce->cart->cart_contents_count); ?>	
							</div> <!-- end cart-content-count -->

							<i class="fa fa-shopping-cart"></i>
						</div>

					</a> <!-- end mobile-cart-content -->
				
					<div class="desktop-cart-content">
						<div class="cart-content-count">
							<?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'mixed'),  $woocommerce->cart->cart_contents_count); ?>	
						</div> <!-- end cart-content-count -->				

						<a href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e('Go to Cart','mixed'); ?>"><i class="fa fa-shopping-cart"></i></a>
					</div> <!-- end desktop-cart-content -->
				

				<nav class="mixed-nav-woocommerce">
				
					<ul class="nav navbar-nav">
						<li>
							<?php if ( is_user_logged_in() ) { ?>
								 <a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account','mixed'); ?>"><?php _e('My Account','mixed'); ?></a>
							<?php } 
							else { ?>
							 	<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Login / Register','mixed'); ?>"><?php _e('Login / Register','mixed'); ?></a>					
							<?php } ?>
						</li>
					</ul>

				</nav>

			<?php } ?>

			<?php wp_nav_menu(array(
				'theme_location' => 'top-menu',							
				'container' => 'nav',							
				'menu_class' => 'nav navbar-nav',
				'depth' => 2,				
			)); ?>			

		</div> <!-- end col-sm-9 -->

	</div> <!-- end container -->

</header> <!-- end top-header -->