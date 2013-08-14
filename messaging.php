<div id="mid" class="clearfix cycle-slideshow" data-cycle-slides="> div" data-cycle-timeout="6000">
	
	<?php if (!is_front_page()): ?>

		<img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="" />

	<?php else: ?>

	    <!-- empty element for pager links -->
	    <span class="cycle-pager"></span>

	    <!-- remove slides_container wrapper if using cycle2 (and data attributes) -->
		<div class="slides_container">
			<?php
				$args = array(
					'post_type'	=> 'homepage-banner',
					'orderby'	=> 'menu_order',
					'order'    	=> 'ASC'
				);
				
				$the_query = new WP_Query($args);
				
				if (!$the_query->have_posts())
				{
					echo sprintf('<div><img src="%s/images/messaging_home.jpg" alt="" /></div>', themedir(false));
				}
				else
				{
					while ($the_query->have_posts()): 
						$the_query->the_post();
						echo sprintf('<div>%s</div>', get_the_content());
					endwhile;
				}

				wp_reset_postdata();
			?>
		</div><!-- / slides_container -->
		
	<?php endif; ?>
	
</div><!-- / mid -->