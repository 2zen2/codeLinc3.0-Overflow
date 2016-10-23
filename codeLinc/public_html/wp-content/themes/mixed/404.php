<?php get_header(); ?>

<div class="container">

	<section class="post-content-wrapper blog-posts error-page" role="main">

		<?php if(is_active_sidebar('sidebar')){ ?>

		<div class="col-sm-9">

		<?php }else{ ?>
			
		<div class="col-sm-9" style="margin:auto;float:none;">

			<?php } ?>
			

			<div class="error-section">
				<fa class="fa fa-unlink fa-2x"></fa>

				<p><?php _e('Page not Found. Please search or go','mixed'); ?> <a href="<?php echo esc_url(home_url('/')); ?>"><?php _e('home','mixed'); ?></a></p>
				
				<?php get_search_form(); ?>	
			
			</div>

		</div> <!-- end col-sm-9 -->

	</section> <!-- end content-main -->


	<?php get_sidebar(); ?>

</div> <!-- end container -->	

<?php if(is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')){ ?>

	<?php get_template_part('bottom','widget'); ?>

<?php } ?>

<?php get_footer(); ?>