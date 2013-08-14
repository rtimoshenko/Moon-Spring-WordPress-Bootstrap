<?php 
	get_header();
	get_template_part('loop', 'header');
?>

<h1><?php _e('Search Results', 'moonspring'); ?></h1>
<p><?php echo _n('%d result for', '%d results for', $wp_query->post_count, 'moonspring'; ?> &ldquo;<strong><?php echo htmlentities($_GET['s']); ?></strong>&rdquo;.</p>

<?php 
	get_template_part('loop', 'search');
	get_template_part('loop', 'footer');
	get_footer(); 
?>