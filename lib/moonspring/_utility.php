<?php
	
	/* ================================================================
		UTILITY
	================================================================  */
	
	function seotitle($postObj = null, $separator = ' ')
	{
		$title = getSEOTitle($postObj, $separator);
		
		if ($title)
			echo sprintf('<title>%1$s</title>', $title) . "\n";
	}
	
	function seokeywords($postObj = null)
	{
		$keywords = getSEOKeywords($postObj);
		
		if ($keywords)
			echo sprintf('<meta name="keywords" content="%1$s" />', $keywords) . "\n";
	}
	
	function seodescription($postObj = null)
	{
		$description = getSEODescription($postObj);
		
		if ($description)
			echo sprintf('<meta name="description" content="%1$s" />', $description) . "\n";
	}
	
	function seocharset()
	{
		$charset = get_bloginfo('charset');
		
		echo sprintf('<meta charset="%1$s" />', $charset) . "\n";
		echo sprintf('<meta http-equiv="content-type" content="text/html; charset=%1$s" />', $charset) . "\n";
	}
	
	function getSEOTitle($postObj = null, $separator = ' ')
	{
		$postObj = getPost($postObj);
			
		if (!$postObj)
			return get_bloginfo('name');
		
		$title = getMetaSingle(SEO_TITLE_KEY, $postObj->ID);
		$echo = false;
		
		if (!$title)
			$title = get_bloginfo('name') . wp_title($separator, $echo);
		
		return $title;
	}
	
	function getSEODescription($postObj = null)
	{
		$postObj = getPost($postObj);
			
		if (!$postObj)
			return get_bloginfo('description');
		
		return getMetaSingle(SEO_DESCRIPTION_KEY, $postObj->ID);
	}
	
	function getSEOKeywords($postObj = null)
	{
		$postObj = getPost($postObj);
			
		if (!$postObj)
			return '';
		
		$output = '';
		$tags = get_the_tags($postObj->ID);
		
		if ($tags) 
		{
			foreach($tags as $tag)
			$output .= $tag->name . ','; 
		}
		
		return rtrim($output, ',');
	
	}
	
	function getMetaSingle($key, $postId = null)
	{
		global $post;
		
		$singleResultOnly = true;
		
		if (!$postId)
			$postId = $post->ID;
		
		return get_post_meta($postId, $key, $singleResultOnly);
	}
	
	function getPost($postObj = null)
	{
		global $post;
		
		if (!$postObj)
			$postObj = $post;
		
		return $postObj;
	}
	
	function navmenu($navId = 'primary', $limitToCurrent = false)
	{
		$locations = get_nav_menu_locations();

		if (empty($locations) || !isset($locations[$navId]))
			return;

		$menu = wp_get_nav_menu_object($locations[$navId]);

		if (!$menu)
			return;
		
		$output = '';
		$childData = null;
		$items = wp_get_nav_menu_items($menu->term_id);

		if (!$items)
			return;
		
		if (!$limitToCurrent)
		{
			$childData = getMenuChildrenForItem($items);
		}
		else if (!empty($items))
		{
			$current = null;
			
			foreach($items as $item)
			{
				if ($item->object_id == get_the_ID())
				{
					if ($item->menu_item_parent == 0)
					{
						$current = $item;
						break;
					}
					else
					{
						foreach($items as $subitem)
						{
							if ($subitem->ID == $item->menu_item_parent)
							{
								$current = $subitem;
								break;
							}
						}
					}
				}
					
			}
			
			if ($current)
				$childData = getMenuChildrenForItem($items, $current, true);
		}
		
			
		if (!empty($childData))
			$output .= $childData['output'];
		
		echo $output;
		return !empty($output);
	}
	
	function catmenu($navId = 'category', $cat = null, $useHeading = true, $noWrap = false)
	{
		echo getCatMenu($navId, $cat, $useHeading, $noWrap);
	}
	
	function getCatMenu($navId = 'category', $cat = null, $useHeading = true, $noWrap = false)
	{
		$output = '';
		$catId = null;
		
		if (is_numeric($cat))
		{
			$catId = $cat;
		}
		else if (!$cat)
		{
			foreach(get_the_category() as $cat)
			{
				if ($cat->parent == 0)
				{
					$catId = $cat->term_id;
					break;
				}
			}
		}
		
		if ($catId)
		{
			$locations = get_nav_menu_locations();
			$menu = wp_get_nav_menu_object($locations[$navId]);
			$items = wp_get_nav_menu_items($menu->term_id);
			$parentId = null;
			$menuParentId = null;
			
			if ($items)
			{
				foreach($items as $item)
				{
					if ($item->object == 'category' && $item->object_id == $catId)
					{
						// Get parent heading
						if ($item->menu_item_parent == '0' && $useHeading)
						{
							$output .= formatNavHeading($item->url, $item->title);
						}
						else
						{
							foreach($items as $subitem)
							{
								if (($subitem->object == 'post' || $subitem->object == 'page') && $subitem->ID == $item->menu_item_parent)
								{
									$parentId = $subitem->object_id;
									$menuParentId = $subitem->ID;
									
									if ($useHeading)
										$output .= formatNavHeading($subitem->url, $subitem->title);
										
									break;
								}
							}
						}
						
						$output .= getCatPosts($item->object_id, ($parentId != null ? array($parentId) : array()));
						
						break;
					}
				}
				
				// Let's see if we had any other subitems
				if ($menuParentId)
				{
					foreach($items as $item)
					{
						if ($item->menu_item_parent == $menuParentId && $item->object != 'category')
						{
							// If we're using a custom item, let's not mess with the url
							$url = ($item->object == 'custom' ? $item->url : get_permalink($item->object_id));
							$id = sprintf('%1$s%2$s', 'cat-item-', $item->object_id);
							$class = activeIf(($item->object_id == get_the_ID()), false);
							$output .= formatNavItem($url, $item->title, $id, $class);
						}
					}
				}
			}
		}
		
		return $output;
	}
	
	function catposts($catId, $exclusions = array(), $prefix = 'cat-item-', $postType = 'any')
	{
		echo getCatPosts($catId, $exclusions, $prefix);
	}
	
	function getCatPosts($catId, $exclusions = array(), $prefix = 'cat-item-', $postType = 'any')
	{
		$output = '';
		
		// Get posts
		$options = array(
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'ASC',
			'post__not_in'		=> (!empty($exclusions) ? $exclusions : array()),
			'post_type'			=> $postType,
			'category__in'		=> $catId
		);
		
		$query = new WP_Query($options);
		
		if ($query->have_posts())
		{
			$output .= '<ul>';
			
			foreach($query->posts as $post)
			{
				$id = sprintf('%1$s%2$s', $prefix, $post->ID);
				$class = activeIf(($post->ID == get_the_ID()), false);
				$output .= formatNavItem(get_permalink($post->ID), $post->post_title, $id, $class);
			}
			
			$output .= '</ul>';
		}
		
		return $output;
	}
	
	function getMenuChildrenForItem($items, $item = null, $includeParent = false)
	{
		$output = '';
		$active = false;
		$itemId = ($item != null ? $item->ID : 0);
		
		if ($item != null)
		{
			if (is_front_page())
			{
				if ($item->object == 'custom')
					$active = ($item->url == '/');
				else if ($item->object == 'post_type')
					$active = ($item->object_id == get_option('page_on_front'));
			}
			
			if ($includeParent)
				$output .= formatNavHeading($item->url, $item->title);
		}
		
		if ($items)
		{
			// Let's get a column count, but only for the main list
			if ($item)
			{
				$output .= '<ul>';
			}
			else
			{
				$colCount = 0;
				
				foreach($items as $subitem)
				{
					if (!$subitem->menu_item_parent)
						$colCount++;
				}
				
				$output .= sprintf('<ul class="cols-%s">', (string)$colCount);
				
				unset($subitem);
				unset($colCount);
			}
			
			foreach($items as $subitem)
			{		
				if ($subitem->menu_item_parent == $itemId)
				{
					if ($subitem->object == 'post' || $subitem->object == 'page' || $subitem->object == 'custom')
					{
						$childData = getMenuChildrenForItem($items, $subitem);
						$subActive = ($subitem->object_id == get_the_ID() || $childData['active']);
						
						if (!$active)
							$active = $subActive;
						
						$id = sprintf('menu-item-%s', $subitem->ID);
						$class = activeIf($subActive, false);
						
						$output .= formatNavItem($subitem->url, $subitem->title, $id, $class, $childData['output']);
					}
					else if ($subitem->object == 'category')
					{
						$childData = getMenuPostsForItem($items, $subitem, $item);
						$subActive = (in_category($subitem->object_id) || $childData['active']);
						
						if (!$active)
							$active = $subActive;
						
						// Get parent heading
						if ($subitem->menu_item_parent != '0')
						{
							$output .= $childData['output'];
						}
						else
						{
							$id = sprintf('menu-item-%s', $subitem->ID);
							$class = activeIf($subActive, false);
							
							$output .= formatNavItem($subitem->url, $subitem->title, $id, $class, $childData['output']);							
						}
					}
				}
			}
			
			$output .= '</ul>';
			
			// If we ended up with an empty list, let's replace it
			if($output == '<ul></ul>')
				$output = '';
		}
		
		return array(
			'output'	=> $output,
			'active'	=> $active
		);
	}
	
	function getMenuPostsForItem($items, $item, $parent = null)
	{
		$output = '';
		$active = false;
		
		$options = array(
			'posts_per_page'	=> -1,
			'orderby'			=> 'title',
			'order'				=> 'ASC',
			'post__not_in'		=> ($parent != null ? array($parent->object_id) : array()),
			'category__in'		=> $item->object_id
		);
		
		
		$query = new WP_Query($options);
		
		if ($query->have_posts())
		{
			foreach($query->posts as $post)
			{
				$subActive = ($post->ID == get_the_ID());
				
				if (!$active)
					$active = $subActive;
					
				$id = sprintf('menu-item-%s', $post->ID);
				$class = activeIf($subActive, false);
				
				$output .= formatNavItem(get_permalink($post->ID), $post->post_title, $id, $class);
			}
		}
		
		return array(
			'output'	=> $output,
			'active'	=> $active
		);
	}
	
	function formatNavItem($url, $title, $id = null, $class = null, $innerContent = null)
	{
		if ($id)
			$id = sprintf(' id="%s"', $id);
			
		if ($class)
			$class = sprintf(' class="%s"', $class);
		
		$output = sprintf('<li%1$s%2$s>', $id, $class);
		$output .= sprintf('<a href="%1$s">%2$s</a>', $url, $title);
		$output .= sprintf('%s</li>', $innerContent);
		
		return $output;
	}
	
	function formatNavHeading($url, $title)
	{
		return sprintf('<h2 class="cat-heading"><a href="%1$s">%2$s</a></h2>', $url, $title);
	}
	
	function activeIf($condition, $echo = true, $class = 'active')
	{
		$value = ($condition ? $class : '');
		
		if (!$echo)
			return $value;
			
		echo $value;
	}
	
	function getFieldAndLabel($name, $label, $type = 'text', $prefix = 'plugin_', $value = '')
	{
		$output = '<div>';
		$output .= sprintf('<label for="%1$s">%2$s</label><br />', $name, __($label, $prefix . $name));
		
		if ($type == 'textarea')
			$output .= sprintf('<textarea id="%1$s" name="%1$s" cols="60" rows="4" style="width: 99%;">%2$s</textarea>', $name, $value);
		else
			$output .= sprintf('<input type="%1$s" id="%2$s" name="%2$s" value="%3$s" size="60" style="width: 99%;" />', $type, $name, $value);
			
		$output .= '</div>';
		
		return $output;
	}
	
	// useful for overriding stylesheet directory for debugging purposes
	function themedir($echo = true)
	{
		$dir = get_bloginfo('stylesheet_directory');
		
		if (!$echo)
			return $dir;
		
		echo $dir;
	}
	
	function catid($index = 0, $echo = false)
	{
		$category = get_the_category();
	
		if (empty($category))
			return null;
			
		$catId = $category[$index]->cat_ID;
		
		if (!$echo)
			return $catId;
		
		echo $catId;
	}
	
	function catname($index = 0, $echo = true)
	{
		$category = get_the_category();
		
		if (empty($category))
			return null;
			
		$catName = $category[$index]->cat_name;
		
		if (!$echo)
			return $catName;
		
		echo $catName;
	}