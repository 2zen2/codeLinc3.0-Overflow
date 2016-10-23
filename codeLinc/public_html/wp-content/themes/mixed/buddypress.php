<?php get_header(); ?>

<div class="container">

	<section class="community-container">

		<?php if(is_active_sidebar('buddypress')){ ?>

		<div class="col-sm-9">

		<?php }else{ ?>
			
		<div class="col-sm-9" style="margin:auto;float:none;">

		<?php } 		


		while(have_posts()):the_post(); ?>		
	
						
			<header class="community-header-title">

				<h3 class="community-title"><?php the_title(); ?></h3>						

			</header>	


			<div class="community-content">

				<?php the_content(); ?>		

			</div>			


		<?php endwhile;	?>									

		</div> <!-- end col-sm-9 -->

	</section> <!-- end content-main -->


	<?php get_sidebar('buddypress'); ?>

</div> <!-- end container -->	

<?php if(is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')){ ?>

	<?php get_template_part('bottom','widget'); ?>

<?php } ?>

<?php get_footer(); ?>