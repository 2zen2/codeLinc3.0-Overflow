<?php if(function_exists('get_field')){ ?>

	<article <?php post_class('h-entry clearfix'); ?> id="post-<?php the_ID(); ?>">

		<div class="entry-content">
			<?php if(get_field('quote_content') && get_field('owner')){ ?>
				<blockquote>
					<?php the_field('quote_content'); ?>

					<cite><?php the_field('owner'); ?></cite>
				</blockquote>
			<?php } ?>					
		</div>					

	</article> <!-- end mixed-article -->	

<?php } ?>