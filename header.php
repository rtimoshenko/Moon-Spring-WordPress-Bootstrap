<!DOCTYPE html>
<html lang="en">
<head>
	<?php 
		// use filter
		//seocharset();
		//seotitle(); 
		//seokeywords();
		//seodescription();
		wp_head(); 
	?>
</head>

<body <?php body_class(); ?>>
	<div id="root">
		<div id="top">
			<a id="brand" href="<?php echo home_url('/'); ?>"><?php bloginfo('name'); ?></a>
			<div class="contact">123-555-1234</div><!-- / contact -->
			<div id="nav">
				<?php if(!navmenu()) wp_page_menu(); ?>
			</div><!-- / nav -->
		</div><!-- / top -->
		<div id="container">
			<?php get_template_part('messaging'); ?>