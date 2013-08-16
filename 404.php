<?php 
	get_header();
	get_template_part('loop', 'posts-header');

	HtmlHelper::pageTitle('Error 404: Page Not Found');
	HtmlHelper::alert('This page has either been moved or does not exist.');
	
	get_template_part('loop', 'posts-footer');
	get_footer();