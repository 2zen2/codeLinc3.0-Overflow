<?php get_header(); ?>

<div class="container">

	<section class="post-content-wrapper single-page">

		<?php if(is_active_sidebar('sidebar')){ ?>

		<div class="col-sm-9">

		<?php }else{ ?>
			
		<div class="col-sm-9" style="margin:auto;float:none;">

		<?php } 
		


		while(have_posts()):the_post(); ?>

			
		<article <?php post_class('h-entry clearfix'); ?> id="post-<?php the_ID(); ?>">
						
			<header class="entry-header">

				<h3 class="entry-title"><?php the_title(); ?></h3>						

			</header>

			<div class="entry-meta col-sm-12">				

				<?php edit_post_link(__('Edit','mixed')); ?>			

				<span class="clearfix"></span>				

			</div> <!-- end entry-meta -->

			<div class="entry-content">
				<?php the_content(); ?>		
			</div>			

		</article>
	

		<?php if(comments_open()){ ?>

			<?php comments_template(); ?>

		<?php }else{ ?>
			<p class="closed-comment-p"><?php _e('Comments closed.','mixed'); ?></p>
		<?php } ?>


		<?php endwhile;	?>
									

		</div> <!-- end col-sm-9 -->

	</section> <!-- end content-main -->


	<?php get_sidebar(); ?>

</div> <!-- end container -->	

<?php if(is_active_sidebar('footer-1') || is_active_sidebar('footer-2') || is_active_sidebar('footer-3') || is_active_sidebar('footer-4')){ ?>

	<?php get_template_part('bottom','widget'); ?>

<?php } ?>

<?php get_footer(); ?>