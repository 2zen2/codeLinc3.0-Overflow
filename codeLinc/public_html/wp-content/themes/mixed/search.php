<?php get_header(); ?>

<div class="container">

	<section class="post-content-wrapper blog-posts" role="main">

		<?php if(is_active_sidebar('sidebar')){ ?>

		<div class="col-sm-9">

		<?php }else{ ?>
			
		<div class="col-sm-9" style="margin:auto;float:none;">

		<?php } ?>

		<div class="breadcrumb">
			<h5><?php _e('Search results for ','mixed'); ?> <?php echo get_search_query(); ?></h5>
		</div>
		

		<?php if(have_posts()):

		while(have_posts()):the_post();

			get_template_part('format',get_post_format());

		endwhile; ?>

		<div class="mixed-pagenavi">

			<?php mixed_pagenavi(); ?>

		</div> <!-- end mixed-pagenavi -->

		<?php else: ?>

		<div class="error-section">

			<fa class="fa fa-minus-circle fa-2x"></fa>

			<p><?php _e('No Posts Found. Please search or go','mixed'); ?> <a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('home','mixed'); ?></a></p>
				
			<?php get_search_form(); ?>	
			
		</div>

		<?php endif; ?>
									

		</div> <!-- end col-sm-9 -->

	</section> <!-- end content-main -->


	<?php get_sidebar(); ?>

</div> <!-- end container -->	

<?php if(is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')){ ?>

	<?php get_template_part('bottom','widget'); ?>

<?php } ?>

<?php get_footer(); ?>