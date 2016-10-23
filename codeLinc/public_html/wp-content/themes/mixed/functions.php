<?php

require_once get_template_directory().'/admin/acf.php';

function mixed_setup() {	

	global $content_width;
	if ( ! isset( $content_width ) ){
		$content_width = 663; 
	}
	
	load_theme_textdomain( 'mixed', get_template_directory() . '/lang' );

	add_theme_support('woocommerce');
	
	add_theme_support( 'automatic-feed-links' );

	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list' ) );	

	add_theme_support('custom-background');

	add_theme_support( 'post-thumbnails' );	

	add_theme_support( 'post-formats', array(
		'aside', 'audio', 'chat', 'image', 'link', 'quote', 'status', 'video'
	) );

	register_nav_menus(array(
		'top-menu' => __( 'Top Menu', 'mixed' ),		
		));
	
}
add_action( 'after_setup_theme', 'mixed_setup' );


/* TGM */
require get_template_directory() . '/admin/admin-init.php';



function mixed_scripts_styles() {
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );	

	
	wp_enqueue_script('smoothscroll',get_template_directory_uri().'/js/smoothscroll.js',array('jquery'),'',true);

	wp_enqueue_script('slider',get_template_directory_uri().'/js/slider.js',array('jquery'),'',true);	

	wp_enqueue_script('fitvids',get_template_directory_uri().'/js/fitvids.js',array('jquery'),'',true);

	wp_enqueue_script('mixed-general-js',get_template_directory_uri().'/js/general.js',array('jquery'),'',true);

	wp_enqueue_script('mixed-custom',get_template_directory_uri().'/js/mixed.js',array('jquery'),'',true);


	wp_enqueue_style( 'mixed-style', get_stylesheet_uri());

	wp_enqueue_style( 'bootstrap-style', get_template_directory_uri().'/css/bootstrap.css');

	wp_enqueue_style( 'fontawesome-style', get_template_directory_uri().'/css/font-awesome.css');

	wp_enqueue_style( 'slider-style', get_template_directory_uri().'/css/slider.css');

	wp_enqueue_style( 'mixed-general-css', get_template_directory_uri().'/css/general.css');	
	
	wp_enqueue_style( 'mixed-woocommerce', get_template_directory_uri().'/css/woocommerce.css');

	wp_enqueue_style( 'mixed-bbpress', get_template_directory_uri().'/css/mixed-bbpress.css');	

	wp_enqueue_style( 'mixed-custom', get_template_directory_uri().'/css/style.css');


	wp_enqueue_style('googleFontsRoboto','//fonts.googleapis.com/css?family=Roboto+Slab');    

	wp_enqueue_style('googleFontsOpenSans','//fonts.googleapis.com/css?family=Open+Sans');

	
}
add_action( 'wp_enqueue_scripts', 'mixed_scripts_styles' );



function mixed_title($title){

	$name=get_bloginfo('title');

	$desc=get_bloginfo('description');

	$title.=$name.' | '.$desc;

	return $title;

}

add_filter('wp_title','mixed_title');



function mixed_excerpt_length( $length ) {
	return 110;
}
add_filter( 'excerpt_length', 'mixed_excerpt_length', 999 );


function new_excerpt_more( $more ) {
	return ' ...';
}
add_filter('excerpt_more', 'new_excerpt_more');


function mixed_widgets(){	
		

	register_sidebar(array(
		'id'         	 => 'sidebar',
	    'name'       	 => __( 'Right Sidebar', 'mixed' ),
	    'description' 	 => __( 'This widget is located right side as sidebar.', 'mixed' ),
	    'before_widget'	 => '<div class="mixed-widget">',
		'after_widget' 	 => '</div>',
		'before_title' 	 => '<h5 class="widget-heading">',
		'after_title'  	 => '</h5>',
		));


	register_sidebar(array(
		'id'         	=> 'footer-1',
	    'name'       	=> __( 'Footer 1', 'mixed' ),
	    'description' 	=> __( 'This widget is located footer.', 'mixed' ),
	    'before_widget'	 => '<div class="mixed-widget">',
		'after_widget' 	 => '</div>',
		'before_title' 	 => '<h5 class="widget-heading">',
		'after_title'  	 => '</h5>',
		));


	register_sidebar(array(
		'id'         	 => 'footer-2',
	    'name'      	 => __( 'Footer 2', 'mixed' ),
	    'description'	 => __( 'This widget is located footer.', 'mixed' ),
	    'before_widget'	 => '<div class="mixed-widget">',
		'after_widget' 	 => '</div>',
		'before_title' 	 => '<h5 class="widget-heading">',
		'after_title'  	 => '</h5>',
		));


	register_sidebar(array(
		'id'         	=> 'footer-3',
	    'name'       	=> __( 'Footer 3', 'mixed' ),
	    'description'	=> __( 'This widget is located footer.', 'mixed' ),
	    'before_widget'	 => '<div class="mixed-widget">',
		'after_widget' 	 => '</div>',
		'before_title' 	 => '<h5 class="widget-heading">',
		'after_title'  	 => '</h5>',
		));


	register_sidebar(array(
		'id'         	=> 'footer-4',
	    'name'       	=> __( 'Footer 4', 'mixed' ),
	    'description'	=> __( 'This widget is located footer.', 'mixed' ),
	    'before_widget'	 => '<div class="mixed-widget">',
		'after_widget' 	 => '</div>',
		'before_title' 	 => '<h5 class="widget-heading">',
		'after_title'  	 => '</h5>',
		));


	register_sidebar(array(
		'id'         	 => 'woocommerce',
	    'name'       	 => __( 'WooCommerce Sidebar', 'mixed' ),
	    'description' 	 => __( 'This widget is located right side as sidebar for WooCommerce content', 'mixed' ),
	    'before_widget'	 => '<div class="mixed-widget">',
		'after_widget' 	 => '</div>',
		'before_title' 	 => '<h5 class="widget-heading">',
		'after_title'  	 => '</h5>',
		));

	register_sidebar(array(
		'id'         	 => 'buddypress',
	    'name'       	 => __( 'BuddyPress Sidebar', 'mixed' ),
	    'description' 	 => __( 'This widget is located right frame as sidebar for BuddyPress', 'mixed' ),
	    'before_widget'	 => '<div class="mixed-widget">',
		'after_widget' 	 => '</div>',
		'before_title' 	 => '<h5 class="widget-heading">',
		'after_title'  	 => '</h5>',
		));

	register_sidebar(array(
		'id'         	 => 'bbpress',
	    'name'       	 => __( 'bbPress Sidebar', 'mixed' ),
	    'description' 	 => __( 'This widget is located right frame as sidebar for bbPress', 'mixed' ),
	    'before_widget'	 => '<div class="mixed-widget">',
		'after_widget' 	 => '</div>',
		'before_title' 	 => '<h5 class="widget-heading">',
		'after_title'  	 => '</h5>',
		));



}

add_action('widgets_init','mixed_widgets');



function mixed_custom_comment_form($defaults) {
	
	
	$defaults['comment_notes_before'] = '';	
	$defaults['id_form'] = 'comment-form';
	$defaults['comment_field'] = '<p><textarea name="comment" id="comment" class="form-control" rows="6"></textarea></p>';

	return $defaults;
}

add_filter('comment_form_defaults', 'mixed_custom_comment_form');

function mixed_custom_comment_fields() {
	$commenter = wp_get_current_commenter();
	$req = get_option('require_name_email');
	$aria_req = ($req ? " aria-required='true'" : '');
	
	$fields = array(
		'author' => '<p>' . 
						'<input id="author" name="author" type="text" class="form-control" placeholder="Name ( required )" value="' . esc_attr($commenter['comment_author']) . '" ' . $aria_req . ' />' .
						
		            '</p>',
		'email' => '<p>' . 
						'<input id="email" name="email" type="text" class="form-control" placeholder="Email ( required )" value="' . esc_attr($commenter['comment_author_email']) . '" ' . $aria_req . ' />'  .
		            '</p>',
		'url' => '<p>' . 
						'<input id="url" name="url" type="text" class="form-control" placeholder="Website" value="' . esc_attr($commenter['comment_author_url']) . '" />'  .
		            '</p>'
	);

	return $fields;
}

add_filter('comment_form_default_fields', 'mixed_custom_comment_fields');



function mixed_footer(){

	global $mixed;

	if(isset($mixed['opt-ace-editor-js'])){

		echo '<script>'.$mixed['opt-ace-editor-js'].'</script>';

	}
	
}

add_action('wp_footer','mixed_footer',30);






function mixed_pagenavi(){

	global $wp_query;

	$big = 999999999;

	echo paginate_links( array(

		'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format' => '?paged=%#%',
		'current' => max( 1, get_query_var('paged') ),
		'total' => $wp_query->max_num_pages,
		'prev_text'    => '<i class="fa fa-caret-left"></i>',
		'next_text'    => '<i class="fa fa-caret-right"></i>',
	));
	
}


//Woocommerce

/* Woocommerce removing the default style */
function mixed_styles( $enqueue_styles ) {
	unset( $enqueue_styles['woocommerce-general'] );
	return $enqueue_styles;
}
add_filter( 'woocommerce_enqueue_styles', 'mixed_styles' );





 function mixed_woocommerce_cols() {
     return 5; 
 }
 add_filter ( 'woocommerce_product_thumbnails_columns', 'mixed_woocommerce_cols' );



  function mixed_related_products_args( $args ) {
 
	$args['posts_per_page'] = 4; 
	$args['columns'] = 4; 
	return $args;
}
 add_filter( 'woocommerce_output_related_products_args', 'mixed_related_products_args' );



function mixed_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	
	ob_start();
	
	?>
	<div class="cart-content-count">
		<?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'mixed'),  $woocommerce->cart->cart_contents_count); ?>	
	</div> <!-- end cart-content-count -->
	<?php
	
	$fragments['div.cart-content-count'] = ob_get_clean();
	
	return $fragments;
	
}
add_filter('add_to_cart_fragments', 'mixed_header_add_to_cart_fragment');



//BuddyPress
function mixed_hide_admin_bar_search () {
	echo '<style type="text/css">
	#adminbarsearch {
		display: none;
	}
	</style>';
}
add_action('admin_head', 'mixed_hide_admin_bar_search');
add_action('wp_head', 'mixed_hide_admin_bar_search');




function mixed_options(){ 

	global $mixed;		

		if(isset($mixed['favicon']['url'])){

			echo '<link rel="shortcut icon" href="'.esc_url($mixed['favicon']['url']).'">';
		
		} ?> 
<style>

	<?php 

	echo strip_tags($mixed['opt-ace-editor-css']); 

	if(isset($mixed['slider-color'])){ 

	if($mixed['slider-color']){ ?>	
		
	.slider-wrapper {	 	
		background-color: <?php echo $mixed['slider-color']; ?>;
	}	

	<?php } } ?>

</style> <?php }

add_action('wp_head','mixed_options');