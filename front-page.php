<?php
	// query_posts('p=1'); // posts hack
	get_header();
	get_template_part('loop', 'posts-header');
	get_template_part('loop', 'posts');
	get_template_part('loop', 'posts-footer');
	get_footer(); 