		</div><!-- / container -->
	</div><!--/ root -->
	
	<div id="footer">
		<div class="wrapper clearfix">
			<?php 
				$options = array(
					'theme_location'	=> 'primary',
					'container'    		=> '',
					'depth'				=> 1
				);
				
				wp_nav_menu($options);
			?>
			
			<?php /* setup_postdata($post =& get_post($id = 51)); ?>
			<?php the_content(); */ ?>

			<?php
				/*$postslist = get_posts('category=7&numberposts=-1');
				foreach ($postslist as $post) : 
					setup_postdata($post);
			?> 
				<a href="<?php the_permalink();?>"><?php the_title();?></a>, 
			<?php endforeach;*/ ?>
		
			<p class="copyright clearfix"><?php HtmlHelper::copyright(); ?></p>
			<?php HtmlHelper::siteAuthor(); ?>
		</div><!-- / wrapper -->
	</div><!-- / footer -->
	<?php wp_footer(); ?>
</body>
</html>