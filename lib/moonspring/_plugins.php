<?php

	$contentPostTypes = array('post', 'page'); // array('post', 'page', 'practicearea', 'attorney');
	
	// Add featured image support to theme
	//add_theme_support('post-thumbnails');
	//add_theme_support('custom-background', array('default-image' => themedir(false) . '/images/bg_body.jpg'));
	//add_theme_support('custom-header', array('default-image' => themedir(false) . '/images/bg_main.jpg', 'uploads' => 'true', 'width' => 960, 'height' => 680));
	//add_theme_support('post-formats', array('aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio'));
	// sample usage: get_template_part('content', get_post_format()); // requires matching template files (i.e., content.php, content-aside.php, content-gallery.php, content-link.php, etc.)
	
	//add_action('customize_register', 'theme_customizer');
	//add_action('init', 'add_custom_post_types', 1); // Set priority to avoid plugin conflicts

	
	// Add menu support
	register_nav_menu('primary', 'Primary Menu');
	//register_nav_menu('category', 'Category Menu');
		
	// Used for messaging, etc.
	//add_image_size('large-feature', 980, 300);
	//add_filter('image_size_names_choose', create_function('$sizes', 'return array_merge($sizes, array(\'large-feature\' => \'Large Feature\'));'));
	
	add_action('wp_dashboard_setup', 'custom_dashboard_widgets');
	
	add_filter('pre_get_posts', 'search_all_post_types');
	add_action('admin_init', 'custom_taxonomies'); // Add cats to pages
	
	add_action('add_meta_boxes', 'seo_meta_box_setup');
	add_action('save_post', 'seo_meta_box_save');
	add_action('widgets_init', 'custom_sidebars');
	
	add_action('wp_enqueue_scripts', 'enqueue_javascript');
	add_action('wp_enqueue_scripts', 'enqueue_style');
	
	

	
	


	/* ================================================================
		CUSTOM THEME OPTIONS
	================================================================  */	
	
	function theme_customizer($wp_customize_manager)
	{
	   	// Add all your settings, sections and controls on to the $wp_customize_manager
	   	$sectionName = 'custom_options_section';
	   	$pageSetting = 'dropdown_pages_setting';
	   
        $wp_customize_manager->add_section($sectionName, array
        (
            'title' => 'Custom Theme Options',
            'priority' => 35
        ));	   

        $wp_customize_manager->add_setting($pageSetting, array
        (
            'default' => '1', // Default setting/value to save
            'type' => 'theme_mod' // Is this an 'option' or a 'theme_mod'? theme_mod is accessed using get_theme_mod( $name, $default )
        ));
        
		$wp_customize_manager->add_control($pageSetting, array
		(
			'label' => 'Homepage Featured Page',
			'section' => $sectionName,
			'type' => 'dropdown-pages',
			'priority' => 5
		));
		// Accessed using get_theme_mod( $name, $default )
	}
	
	
	
	
	
	/* ================================================================
		CUSTOM META BOXES
	================================================================  */
	
	function seo_meta_box_setup()
	{
		global $contentPostTypes;
		$id = 'seo_meta_box'; // css id
		$title = 'SEO Settings';
		$callback = 'seo_meta_box_implementation';
		$context = 'advanced';
		$priority = 'default';
		$callback_args = array();
		
		// add a meta box for each of the wordpress page types: posts and pages
		foreach ($contentPostTypes as $post_type)
		{
			add_meta_box($id, $title, $callback, $post_type, $context, $priority, $callback_args);
		}
	}
	
	function seo_meta_box_implementation($post, $meta)
	{
		// Use nonce for verification
		wp_nonce_field('-1', 'seo_settings_nonce');
		
		$descriptionValue = getMetaSingle(SEO_DESCRIPTION_KEY, $post->ID);
		$titleValue = getMetaSingle(SEO_TITLE_KEY, $post->ID);
		
		// The actual fields for data entry
		echo getFieldAndLabel(SEO_DESCRIPTION_KEY, 'SEO Description', 'text', 'seo_', $descriptionValue);
		echo '<br />';
		echo getFieldAndLabel(SEO_TITLE_KEY, 'SEO Title', 'text', 'seo_', $titleValue);
	}
	
	function seo_meta_box_save($post_id)
	{
		// verify if this is an auto save routine. 
		// If it is our form has not been submitted, so we dont want to do anything
		if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
		  return;
		
		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if (!wp_verify_nonce($_POST['seo_settings_nonce']))
		  return;
		
		
		// Check permissions
		if ($_POST['post_type'] == 'page') 
		{
			if (!current_user_can('edit_page', $post_id))
				return;
		}
		else
		{
			if (!current_user_can('edit_post', $post_id))
				return;
		}
		
		// Update meta with $_POST data
		update_post_meta($post_id, SEO_DESCRIPTION_KEY, $_POST[SEO_DESCRIPTION_KEY]);
		update_post_meta($post_id, SEO_TITLE_KEY, $_POST[SEO_TITLE_KEY]);
	}
	
	
	
	
	
	/* ================================================================
		CUSTOM POST TYPES
	================================================================  */
	
	function add_custom_post_types() 
	{ 
		addCustomPostType('Homepage Banner', null, array('title', 'editor', 'page-attributes'));
	}
	
	// Make custom post type searchable
	function search_all_post_types($query) 
	{
		if ($query->is_search) 
		{ 
			$query->set('post_type', $contentPostTypes); 
		}
		
		return $query;
	}
	
	function addCustomPostType($singleLabel, $pluralLabel = null, $supports = null)
	{
		if (!$singleLabel)
			return;	
		
		if (!$pluralLabel)
			$pluralLabel = $singleLabel . 's';
			
		$single = strtolower(str_ireplace(' ', '-', $singleLabel));
		$plural = strtolower(str_ireplace(' ', '-', $pluralLabel));
		
		$labels = array( // Used in the WordPress admin
			'name' => _x($pluralLabel, 'post type general name'),
			'singular_name' => _x($singleLabel, 'post type singular name'),
			'add_new' => _x('Add New', $singleLabel),
			'add_new_item' => __('Add New ' . $singleLabel),
			'edit_item' => __('Edit ' . $singleLabel),
			'new_item' => __('New ' . $singleLabel),
			'view_item' => __('View ' . $singleLabel),
			'search_items' => __('Search ' . $pluralLabel),
			'not_found' =>  __('Nothing found'),
			'not_found_in_trash' => __('Nothing found in Trash')
		);
		
		$args = array(
			'labels' => $labels, // Set above
			'public' => true, // Make it publicly accessible
			'hierarchical' => true, // No parents and children here
			'menu_position' => 5, // Appear right below "Posts"
			'rewrite' => array( 'slug' => $single,'with_front' => FALSE),
			'capability_type' => 'post',
			'taxonomies' => array('category'),
			'has_archive' => $plural, // Activate the archive
			'supports' => (!empty($supports) ? $supports : array('title', 'editor', 'thumbnail', 'custom-fields'))
		);
		
		register_post_type($single, $args); // Create the post type, use options above
		//flush_rewrite_rules(); // run once if pages return a 404
	}
	
	
	
	
	/* ================================================================
		CUSTOM WIDGETS
	================================================================  */
	
	function custom_dashboard_widgets() 
	{
		// Global the $wp_meta_boxes variable (this will allow us to alter the array)
		global $wp_meta_boxes;
		
		wp_add_dashboard_widget('custom_help_widget', 'Help and Support', 'custom_dashboard_help');
		
		/*// Then we make a backup of your widget
		$my_widget = $wp_meta_boxes['dashboard']['normal']['core']['custom_help_widget'];
		
		// We then unset that part of the array
		unset($wp_meta_boxes['dashboard']['normal']['core']['custom_help_widget']);
		
		// Now we just add your widget back in
		$wp_meta_boxes['dashboard']['side']['core']['custom_help_widget'] = $my_widget;*/
	}
	
	function custom_dashboard_help() 
	{
	   echo sprintf('<p>Welcome to your custom theme! Need help? Contact <a href="%1$s">%2$s</a>.</p>', CMS_DISTRIBUTOR_URL, CMS_DISTRIBUTOR_NAME);
	}
	
	function enqueue_javascript() 
	{
		$protocol = (empty($_SERVER["HTTPS"]) ? 'http:' : 'https:');
		
	    // keep jQuery in header for plugin compatability
        register_theme_script('jquery', $protocol . '//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js');
        //register_theme_script('jquery-ui', $protocol . '//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js');
        //register_theme_script('jquery-cycle', $protocol . '//ajax.aspnetcdn.com/ajax/jquery.cycle/2.99/jquery.cycle.all.min.js', null, null, true);
        //register_theme_script('jquery-cycle2', themedir(false) . '/js/jquery.cycle2.min.js', array('jquery'), '1.0', false);
        register_theme_script('theme-script', themedir(false) . '/js/script.js', array('jquery'), '1.0', true);
	}

    function register_theme_script($key, $src, $dependencies = null, $version = '1.0', $in_footer = false)
    {
        wp_deregister_script($key);
        wp_register_script($key, $src, $dependencies, $version, $in_footer);
        wp_enqueue_script($key);
    }
	
	function enqueue_style()
	{
		try 
		{
			// Compiled CSS files
			if (!class_exists('lessc')) require('lessc.inc.php');
			
			$formatter = new lessc_formatter_classic;
			$formatter->indentChar = "\t";
			
			$less = new lessc;
			$less->setFormatter($formatter);
		    $less->checkedCompile(get_template_directory() . '/css/style-dynamic.css.less', get_template_directory() . '/css/style-dynamic.css');
		} 
		catch (Exception $ex) 
		{
		    echo "lessphp fatal error: ".$ex->getMessage();
		    exit;
		}
	
        wp_register_style('theme-style', themedir(false) . '/css/style-dynamic.css');
        wp_enqueue_style('theme-style');
	}
	
	
	
	
	/* ================================================================
		CUSTOM SIDEBARS
	================================================================  */
	
	function custom_sidebars()
	{
		if (function_exists('register_sidebar'))
		{
			register_sidebar(array(
				'id' => 'custom-sidebar',
				'name' => 'Custom Sidebar',
				'before_widget' => '<div id="%1$s" class="widget %2$s">',
				'after_widget' => '</div>',
				'before_title' => '',
				'after_title' => ''
			));
		}
	}
	
	
	
	/* ================================================================
		CUSTOM TAXONOMIES
	================================================================  */
		
	
	function custom_taxonomies() 
	{
		// Adds categories to pages
		register_taxonomy_for_object_type('category', 'page');
		add_post_type_support('page', 'category');
	}