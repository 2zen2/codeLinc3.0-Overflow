<?php 



$slider_posts=get_posts(array(

	'post_type' => 'post',
	'posts_per_page' => 10,
	'meta_key' => 'slider_check'

)); 

?>

<?php if(function_exists('get_field') && !empty($slider_posts)){ ?>

<div class="section-slider">

	<div class="slider-wrapper" role="slider">
					
		<div class="responsive-container">	

			<div class="slider">

				<div class="fs_loader"></div>

				<?php foreach($slider_posts as $post){

				$slider_check=get_field('slider_check',$post->ID);

				$image_one=get_field('image_one',$post->ID);

				$image_two=get_field('image_two',$post->ID);

				$image_three=get_field('image_three',$post->ID);

				if($slider_check){

				?>				

				<div class="slide">							

					<?php if($image_one['url']){ ?>
						<img src="<?php echo $image_one['url']; ?>"
											width="275" height="275"			
											data-position="100,50" data-in="right" data-delay="100" data-out="right">
					<?php } ?>

					<?php if($image_two['url']){ ?>
					<img src="<?php echo $image_two['url']; ?>"
											width="350" height="350"			
											data-position="10,300" data-in="left" data-delay="200" data-out="right">
					<?php } ?>

					<?php if($image_three['url']){ ?>											
					<img src="<?php echo $image_three['url']; ?>" 
											width="275" height="275" 		
											data-position="100,630" data-in="right" data-delay="300" data-out="right">
					<?php } ?>

					<?php if(get_field('text_one')){ ?>									
					<p class="claim blue"	
											data-position="30,40" data-in="left" data-step="1" data-out="left"><?php the_field('text_one'); ?></p>
					<?php } ?>

					<?php if(get_field('text_two')){ ?>																		
					<p class="teaser orange" 	
											data-position="90,40" data-in="left" data-step="1" data-delay="200" data-out="left"><?php the_field('text_two'); ?></p>
					<?php } ?>

					<?php if(get_field('text_three')){ ?>	
					<p class="teaser orange" 	
											data-position="150,40" data-in="left" data-step="1" data-delay="400" data-out="left"><?php the_field('text_three'); ?></p>						
					<?php } ?>	
					
					<?php if(get_field('button_link') || get_field('link_title')){ ?>
					<a href="<?php echo esc_url(get_field('button_link')); ?>" class="btn btn-default" target="_blank" 
					data-position="300,400" data-in="top" data-step="1" data-delay="400" data-out="none"><?php the_field('link_title'); ?></a>
					<?php } ?>

				</div> <!-- end slide -->

				<?php } } ?>
					
			</div>

		</div>

	</div> <!-- end slider-wrapper -->

</div> <!-- end section-slider -->

<?php } ?>