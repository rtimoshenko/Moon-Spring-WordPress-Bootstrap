<?php if (have_posts()) while (have_posts()): ?>

	<div class="post">	
		<?php
			the_post();
			the_title('<h2>' . sprintf('<a href="%s">', get_permalink()), '</a></h2>');
			the_excerpt();
			echo sprintf('<p><a href="%1$s">%2$</a></p>', get_permalink(), __('View page', 'moonspring'));
		?>
	</div><!-- / post -->

<?php endwhile; ?>