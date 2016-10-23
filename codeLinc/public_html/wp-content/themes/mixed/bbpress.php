<?php get_header(); ?>

<div class="container">

	<section class="post-content-wrapper bbpress-wrapper">

		<?php if(is_active_sidebar('bbpress')){ ?>

		<div class="col-sm-9">

		<?php }else{ ?>
			
		<div class="col-sm-11" style="margin:auto;float:none;">

		<?php } 
		


		while(have_posts()):the_post(); ?>

			
		<div <?php post_class('clearfix'); ?>>
						
			<header class="entry-header">

				<h3 class="entry-title"><?php the_title(); ?></h3>						

			</header>

			<div class="entry-content">
				<?php the_content(); ?>		
			</div>			

		</div>

		<?php endwhile;	?>									

		</div> <!-- end col-sm-9 -->

	</section> <!-- end content-main -->


	<?php get_sidebar('bbpress'); ?>

</div> <!-- end container -->	

<?php if(is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')){ ?>

	<?php get_template_part('bottom','widget'); ?>

<?php } ?>

<?php get_footer(); ?>
