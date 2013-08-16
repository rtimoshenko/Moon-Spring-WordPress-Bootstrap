<?php
	// Used as front-page regardless of whether home config is set to blog posts or static page
	// http://codex.wordpress.org/Template_Hierarchy
	get_header();
	get_template_part('loop', 'posts-header');
	get_template_part('loop', 'posts');
	get_template_part('loop', 'posts-footer');
	get_footer(); 