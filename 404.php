<?php 
	get_header();
	get_template_part('loop', 'posts-header');
?>

<h1><?php _e('Error 404: Page Not Found', 'moonspring'); ?></h1>
<p><?php _e('This page has either been moved or does not exist.', 'moonspring'); ?></p>

<?php
	get_template_part('loop', 'posts-footer');
	get_footer(); 
?>