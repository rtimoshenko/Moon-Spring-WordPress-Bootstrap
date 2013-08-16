<?php
	// Blog posts index (blog home)
	// http://codex.wordpress.org/Template_Hierarchy
	get_header();
	get_template_part('loop', 'header');
?>

<h1><?php _e('Blog', 'moonspring'); ?></h1>

<?php 
	get_template_part('loop', 'subposts');
	get_template_part('loop', 'footer');
	get_footer(); 
?>