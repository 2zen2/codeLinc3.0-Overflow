<?php get_header(); ?>

<div class="container">

	<section class="shop-wrapper">

		<?php if(is_active_sidebar('woocommerce')){ ?>

		<div class="col-sm-9">

		<?php }else{ ?>
			
		<div class="col-sm-12" style="margin:auto;float:none;">

		<?php } ?>			
				
			<?php woocommerce_content(); ?>										

		</div> <!-- end col-sm-9 -->

	</section> <!-- end content-main -->


	<?php get_sidebar('woocommerce'); ?>

</div> <!-- end container -->	

<?php if(is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')){ ?>

	<?php get_template_part('bottom','widget'); ?>

<?php } ?>

<?php get_footer(); ?>