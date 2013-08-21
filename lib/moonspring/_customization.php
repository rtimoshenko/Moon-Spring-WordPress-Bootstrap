<?php
	
	global $user_login;
	get_currentuserinfo();
	
	
	if (MINIMAL_MODE):
		add_action('wp_dashboard_setup', 'remove_dashboard_widgets');
		add_action('admin_menu','remove_default_post_screen_metaboxes');
		add_action('admin_menu','remove_default_page_screen_metaboxes');
		add_action('admin_menu', 'remove_menus');
		add_action('wp_before_admin_bar_render', 'remove_admin_bar_menus');
		add_action('init', 'remove_header_info');
		
		// hook the translation filters
		add_filter('gettext','rename_posts', 10, 3);
		add_filter('ngettext','rename_posts', 10, 3);
		add_filter('gettext', 'remove_howdy', 10, 3);
		add_filter('ngettext', 'remove_howdy', 10, 3);
		
		add_action('do_feed', 'remove_feed', 1);
		add_action('do_feed_rdf', 'remove_feed', 1);
		add_action('do_feed_rss', 'remove_feed', 1);
		add_action('do_feed_rss2', 'remove_feed', 1);
		add_action('do_feed_atom', 'remove_feed', 1);
	endif;

	if (DISABLE_COMMENTS):	
		add_filter('comments_number', create_function('$data', "return false;"));
		add_filter('comments_open', create_function('$data', "return false;"));
		add_action('admin_menu', 'remove_comment_menus');
		add_action('admin_head', 'remove_comment_styles');
	endif;
	
	// Style, logo, etc.
	if (WHITELIST):
		add_filter('login_headerurl', create_function('$url', "return CMS_DISTRIBUTOR_URL;"));
		add_filter('login_headertitle', create_function('$title', "return get_bloginfo('name') . ' powered by ' . CMS_DISTRIBUTOR_NAME;"));
		add_action('login_head', 'custom_login_style');
		add_action('admin_head', 'custom_admin_styles');
		add_action('admin_head', 'custom_admin_favicon');
		add_filter('admin_footer_text', 'custom_admin_footer');
		
		// Remove version info from head and feeds (security)
		add_filter('the_generator', create_function('', "return '';"));
	endif;
	
	add_filter('widget_title', 'custom_widget_title');
	add_filter('list_terms_exclusions', 'exclude_default_cat');
	add_filter('excerpt_length', 'custom_excerpt_length', 999);
	add_filter('excerpt_more', 'new_excerpt_more');
	
	// Remove update notification for all users except for sysadmins
	if (!current_user_can('update_plugins')) 
	{ 
		// checks to see if current user can update plugins 
		add_action('init', create_function('$a', "remove_action('init', 'wp_version_check');"), 2);
		add_filter('pre_option_update_core', create_function('$a', "return null;"));
	}
	
	
	
	
	/* ================================================================
		CLEANUP & CUSTOMIZATION
	================================================================  */
	
	// Remove extraneous menus
	function remove_menus()
	{
		global $menu;
	
		$hidePages = false;
		$hidePosts = false;
		
		
		$items = array(
			//__('Media'),
			__('Links')
		);
		
		if ($hidePages)
			$items[] = __('Pages');
		else if ($hidePosts)
			$items[] = __('Posts');
		
		
		if (!current_user_can('add_users'))
			$items[] = __('Users');
		
		if (!current_user_can('switch_themes'))
			$items[] = __('Appearance');
		
		if (!current_user_can('manage_options'))
		{
			$items[] = __('Settings');
			$items[] = __('Tools');
			$items[] = __('Plugins');
		}
		
	
		end ($menu);
		while (prev($menu))
		{
			$value = explode(' ',$menu[key($menu)][0]);
			
			if(in_array($value[0] != NULL ? $value[0] : "" , $items))
				unset($menu[key($menu)]);
		}// end while
		
		
		// Add categories section under pages
		if ($hidePosts)
			add_pages_page('Categories', 'Categories', 'edit_pages', 'edit-tags.php?taxonomy=category', null);
		
		// Remove widgets
		//remove_submenu_page('themes.php', 'widgets.php');
	}
	
	function remove_comment_menus()
	{
		global $menu;
		global $wp_admin_bar;
	
		$items = array(
			__('Comments')
		);
	
		end ($menu);
		while (prev($menu))
		{
			$value = explode(' ',$menu[key($menu)][0]);
			
			if(in_array($value[0] != NULL ? $value[0] : "" , $items))
				unset($menu[key($menu)]);
		}// end while

		
		
		// Remove comments menus
		remove_menu_page('edit-comments.php');
		remove_submenu_page('options-general.php', 'options-discussion.php');
		
		remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
		remove_meta_box('commentstatusdiv','post','normal'); // Comments Metabox
		remove_meta_box('commentstatusdiv','page','normal'); // Comments Metabox

		try {
			if (is_object($wp_admin_bar))
				$wp_admin_bar->remove_menu('comments');
		} catch(Exception $e) {}
	}
	
	// Removes unnecessary dashboard widgets
	function remove_dashboard_widgets() 
	{
		remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
		remove_meta_box('dashboard_primary', 'dashboard', 'side');
		remove_meta_box('dashboard_secondary', 'dashboard', 'side');
		remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
		remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
		remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
		remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
		remove_meta_box('dashboard_plugins', 'dashboard', 'normal');
	}
	
	// Remove default meta boxes from posts
	function remove_default_post_screen_metaboxes() 
	{
		remove_meta_box('postcustom','post','normal'); // Custom Fields Metabox
		//remove_meta_box('slugdiv','post','normal'); // Slug Metabox
		//remove_meta_box('authordiv','post','normal'); // Author Metabox
		//remove_meta_box('postexcerpt','post','normal'); // Excerpt Metabox
		remove_meta_box('trackbacksdiv','post','normal'); // Talkback Metabox
	}
	
	// Remove default meta boxes from pages
	function remove_default_page_screen_metaboxes() 
	{
		remove_meta_box('postcustom','page','normal'); // Custom Fields Metabox
		//remove_meta_box('slugdiv','page','normal'); // Slug Metabox
		//remove_meta_box('authordiv','page','normal'); // Author Metabox
		//remove_meta_box('postexcerpt','page','normal'); // Excerpt Metabox
		remove_meta_box('trackbacksdiv','page','normal'); // Talkback Metabox
	}
	
	// Remove unncessary header info
	function remove_header_info() 
	{
		remove_action('wp_head', 'rsd_link');
		remove_action('wp_head', 'wlwmanifest_link');
		remove_action('wp_head', 'wp_generator');
		remove_action('wp_head', 'start_post_rel_link');
		remove_action('wp_head', 'index_rel_link');
		remove_action('wp_head', 'adjacent_posts_rel_link');
	}
	
	// Remove feeds
	function remove_feed() 
	{
		wp_die( __('No feed available, please visit our <a href="'. get_bloginfo('url') .'">homepage</a>.') );
	}
	
	// Remove WP menus from admin bar
	function remove_admin_bar_menus()
	{
		global $wp_admin_bar;
		
		$wp_admin_bar->remove_menu('wp-logo');
		$wp_admin_bar->remove_menu('about');
		$wp_admin_bar->remove_menu('wporg');
		$wp_admin_bar->remove_menu('documentation');
		$wp_admin_bar->remove_menu('support-forums');
		$wp_admin_bar->remove_menu('feedback');
		//$wp_admin_bar->remove_menu('view-site');
	}
	
	// Excludes 'Uncategorized' items from public facing pages
	function exclude_default_cat($exclusions) 
	{
		if(!is_admin())
			$exclusions .=  "AND t.term_id != " . get_option('default_category') . " ";
			
		return $exclusions;
	}
	
	// Replace "howdy" with "welcome"
	function remove_howdy($translated, $text, $domain) 
	{
		if (!is_admin() || 'default' != $domain)
			return $translated;
	
		if (false !== strpos($translated, 'Howdy'))
			return str_replace('Howdy', 'Welcome', $translated);
	
		return $translated;
	}
	
	// Replace's instances of "Post" with custom title
	function rename_posts($translated)
	{
		//$translated = str_ireplace('Post','Article', $translated );// ireplace is PHP5 only
		return $translated;
	}
	
	// Replaces the login header logo and adjusts any other style tweaks
	function custom_login_style() 
	{
		echo sprintf('<style type="text/css">.login h1 a {height: 100px; background-image: url(%1$s/lib/cms_logo.png) !important; background-size: auto;}</style>', get_template_directory_uri());
	}
	
	// Custom admin css
	function custom_admin_styles() 
	{
		echo '<style type="text/css">#wp-admin-bar-wp-logo{display: none;}</style>';
	}
	
	function remove_comment_styles() 
	{
		echo '<style type="text/css">.column-comments, #wp-admin-bar-comments {display: none;}</style>';
	}
	
	// Custom favicon
	function custom_admin_favicon() 
	{
		echo sprintf('<link rel="shortcut icon" type="image/x-icon" href="%1$s/lib/cms_logo.png" />', get_template_directory_uri());
	}
	
	// Customize admin footer text
	function custom_admin_footer() 
	{
		echo sprintf('Powered by <a href="%1$s">%2$s</a>', CMS_DISTRIBUTOR_URL, CMS_DISTRIBUTOR_NAME);
	}

	// Custom widget title	
	function custom_widget_title($title) 
	{
		return sprintf('<h3>%s</h3>', $title);
	}

	function custom_excerpt_length($length)
	{
		return 20;
	}
	
	function new_excerpt_more($more) 
	{
		return '...';
	}