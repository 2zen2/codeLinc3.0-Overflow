<article <?php post_class('h-entry clearfix'); ?> id="post-<?php the_ID(); ?>">
						
	<header class="entry-header">

		<?php if(is_sticky()){ ?>

			<h1 class="entry-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h1>						

		<?php }else{ ?>

			<h3 class="entry-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>	

		<?php } ?>

	</header>

	<?php if(has_post_thumbnail()){ ?>

		<figure class="col-sm-6 entry-featured-image">								
			
			<?php the_post_thumbnail(); ?>

			<div class="overlay">					
				<a href="<?php the_permalink(); ?>" class="overlay-link">
					<i class="fa fa-link fa-2x"></i>
				</a>
			</div>			

		</figure> <!-- end entry-featured-image -->

	<?php } ?>


	<div class="entry-summary">
		<?php echo get_the_excerpt(); ?>		
	</div>

	<div class="entry-meta col-sm-12">

		<span class="p-author"><i class="fa fa-user"></i><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" rel="author"><?php the_author(); ?></a></span>

		<span class="sep">|</span>					
														
		<span class="dt-published"><i class="fa fa-calendar-o"></i><?php the_date(); ?></span>	

		<?php if(comments_open()) { ?>
			<span class="sep">|</span>

			<span class="span-comment"><i class="fa fa-comment"></i><?php comments_number(__('0', 'mixed'),__('1', 'mixed'),'%'); ?></span>
		<?php } ?>

		<span class="u-url"><a href="<?php the_permalink(); ?>"><?php _e('Read More','mixed'); ?> <i class="fa fa-angle-double-right"></i></a></span>	

		<span class="clearfix"></span>				

	</div> <!-- end entry-meta -->

</article>