<?php if(function_exists('get_field')){ ?>
	<article <?php post_class('h-entry clearfix'); ?> id="post-<?php the_ID(); ?>">

		<?php if(get_field('add_link') && get_field('how_to_appear')){ ?>				

			<div class="entry-content">
				<a href="<?php echo esc_url(get_field('add_link')); ?>" title="<?php _e('Go to link','mixed'); ?>" target="_blank"><?php the_field('how_to_appear'); ?></a>
			</div>	

		<?php } ?>			

	</article>
<?php } ?>