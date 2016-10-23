<?php global $mixed; ?>

<footer class="bottom-footer" role="contentinfo">
			
	<div class="container">		

		<div class="col-sm-5">
					
			<?php if(isset($mixed['facebook'])){ ?>

			<div class="social-list">
						
				<ul>
					<?php if($mixed['facebook']){ ?>
						<li><a href="<?php echo esc_url($mixed['facebook']); ?>" title="Facebook" target="_blank"><i class="fa fa-facebook"></i></a></li>
					<?php } ?>
					<?php if($mixed['twitter']){ ?>
						<li><a href="<?php echo esc_url($mixed['twitter']); ?>" title="Twitter" target="_blank"><i class="fa fa-twitter"></i></a></li>
					<?php } ?>
					<?php if($mixed['instagram']){ ?>
						<li><a href="<?php echo esc_url($mixed['instagram']); ?>" title="Instagram" target="_blank"><i class="fa fa-instagram"></i></a></li>
					<?php } ?>
					<?php if($mixed['pinterest']){ ?>
						<li><a href="<?php echo esc_url($mixed['pinterest']); ?>" title="Pinterest" target="_blank"><i class="fa fa-pinterest"></i></a></li>
					<?php } ?>
					<?php if($mixed['linkedin']){ ?>
						<li><a href="<?php echo esc_url($mixed['linkedin']); ?>" title="LinkedIn" target="_blank"><i class="fa fa-linkedin"></i></a></li>
					<?php } ?>
					<?php if($mixed['rss']){ ?>
						<li><a href="<?php echo esc_url($mixed['rss']); ?>" title="RSS" target="_blank"><i class="fa fa-rss"></i></a></li>
					<?php } ?>
					<?php if($mixed['behance']){ ?>
						<li><a href="<?php echo esc_url($mixed['behance']); ?>" title="Behance" target="_blank"><i class="fa fa-behance"></i></a></li>
					<?php } ?>
					<?php if($mixed['github']){ ?>
						<li><a href="<?php echo esc_url($mixed['github']); ?>" title="Github" target="_blank"><i class="fa fa-github"></i></a></li>
					<?php } ?>
					<?php if($mixed['google']){ ?>
						<li><a href="<?php echo esc_url($mixed['google']); ?>" title="Google +" target="_blank"><i class="fa fa-google-plus"></i></a></li>
					<?php } ?>
					<?php if($mixed['wordpress']){ ?>
						<li><a href="<?php echo esc_url($mixed['wordpress']); ?>" title="Wordpress" target="_blank"><i class="fa fa-wordpress"></i></a></li>
					<?php } ?>
					<?php if($mixed['youtube']){ ?>
						<li><a href="<?php echo esc_url($mixed['youtube']); ?>" title="Youtube" target="_blank"><i class="fa fa-youtube"></i></a></li>
					<?php } ?>
					<?php if($mixed['flickr']){ ?>
						<li><a href="<?php echo esc_url($mixed['flickr']); ?>" title="Flickr" target="_blank"><i class="fa fa-flickr"></i></a></li>
					<?php } ?>
				</ul>

			</div> <!-- end social-list -->

			<?php } ?>

		</div> <!-- end col-sm-5 -->

		<div class="col-sm-7">

			<?php if(isset($mixed['opt-editor'])){ ?>
					
				<?php if($mixed['opt-editor']){ ?>

					<div class="copyright">			
				
						<?php echo $mixed['opt-editor']; ?> |

					</div> <!-- end copyright -->

				<?php } ?>

			<?php } ?>

			<?php if(isset($mixed['credit'])){ 

				if($mixed['credit']==true){ ?>

					<div class="site-generator">
						<span><?php _e('Theme by','mixed'); ?> <a href='<?php echo esc_url("http://burak-aydin.com"); ?>' target="_blank" rel="generator">Burak Aydin</a></span>
						<span class="sep">|</span>
						<span><?php _e('Powered by','mixed'); ?> <a href="<?php echo esc_url('http://wordpress.org'); ?>" target="_blank" rel="generator">WordPress</a></span>
					</div> <!-- end site-generator -->

				<?php } 

				 }else{ ?>

				<div class="site-generator">
					<span><?php _e('Theme by','mixed'); ?> <a href='<?php echo esc_url("http://burak-aydin.com"); ?>' target="_blank" rel="generator">Burak Aydin</a></span>
					<span class="sep">|</span>
					<span><?php _e('Powered by','mixed'); ?> <a href="<?php echo esc_url('http://wordpress.org'); ?>" target="_blank" rel="generator">WordPress</a></span>
				</div> <!-- end site-generator -->

			<?php } ?>

		</div> <!-- end col-sm-7 -->						

	</div> <!-- end container -->

</footer> <!-- end bottom-footer -->
    
	
<?php wp_footer(); ?>
</body>
</html>