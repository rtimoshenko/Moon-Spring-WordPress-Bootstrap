<?php 
	get_header();
	get_template_part('loop', 'header');

	HtmlHelper::pageTitle('Archive');
	
	get_template_part('loop', 'subposts');
	get_template_part('loop', 'footer');
	get_footer(); 