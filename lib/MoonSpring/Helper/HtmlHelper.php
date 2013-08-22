<?php namespace MoonSpring\Helper;

class HTMLHelper
{
	private static $_config = null;
	private static $_textDomain = 'moonspring';

	public __construct(MSConfig $config)
	{
		$this->_config = $config;
	}

	public static pageTitle($title)
	{
		$title = __($title, $this->_textDomain);

		echo '<h1>{$title}</h1>';
	}

	public static alert($message, $type = 'error')
	{
		$message = __($message, $this->_textDomain);

		echo "<div class='alert alert-{$type}'>{$message}</div>";
	}

	public static copyright($message, $suffix = 'All Rights Reserved.')
	{
		$date = date('Y');
		$siteName = get_bloginfo('name');
		$suffix = __($suffix, $this->_textDomain);

		echo "&copy {$date} {$siteName}. {$suffix}";
	}

	public static siteAuthor()
	{
		$url = '';
		$name = '';
		$prefix = __('site by', $this->_textDomain);

		echo "{$prefix} <a target='_blank' rel='nofollow' href='{$url}'>{$name}</a>";
	}
}