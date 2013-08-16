<?php
	// Index page is a catch-all (i.e., a template wasn't defined for this request)
	// http://codex.wordpress.org/Template_Hierarchy
	get_header();
	get_template_part('loop', 'header');
	get_template_part('loop', 'subposts');
	get_template_part('loop', 'footer');
	get_footer();