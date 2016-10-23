<?php get_header(); ?>

<div class="container">

	<section class="post-content-wrapper single-post">

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

				<span class="p-author"><i class="fa fa-user"></i><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php the_author(); ?></a></span>

				<span class="sep">|</span>					
																
				<span class="dt-published"><i class="fa fa-calendar-o"></i><?php the_date(); ?></span>		

				<?php if(comments_open()) { ?>
					<span class="sep">|</span>

					<span class="span-comment"><i class="fa fa-comment"></i><?php comments_number(__('0', 'mixed'),__('1', 'mixed'),'%'); ?></span>
				<?php } ?>	

				<?php edit_post_link(__('Edit','mixed'),'<span class="edit-post-span"> | ','</span>'); ?>			

				<span class="clearfix"></span>				

			</div> <!-- end entry-meta -->

			<div class="entry-content">
				<?php the_content(); ?>

				<?php if(function_exists('get_field')){ ?>
	
					<?php if(get_field('status_content')){ ?>

						<?php the_field('status_content'); ?>

					<?php } ?>

				<?php } ?>

				<div class="single-link-pages">
					
					<?php wp_link_pages(); ?>

				</div> <!-- end single-link-pages -->	
			</div>			

		</article>

		<div class="single-category-area">
			<span class="u-category"><i class="fa fa-briefcase"></i><?php the_category(); ?></span>	
		</div> <!-- end single-category-area -->

		<?php if(has_tag()){ ?>

			<div class="single-tags">
				<?php the_tags(__('Tags : ','mixed'),'  ',''); ?>
			</div>

		<?php } ?>


		<div class="next-prev-posts clearfix">

			<div class="prev-post">
				<?php previous_post_link('<i class="fa fa-angle-double-left"></i>%link'); ?>
			</div>

			<div class="next-post">
				<?php next_post_link('%link<i class="fa fa-angle-double-right"></i>'); ?>
			</div>

		</div>


	

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