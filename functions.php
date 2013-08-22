<?php

	//error_reporting(E_ALL);
	//ini_set('display_errors', '1');
	$baseDir = get_template_directory() . '/lib/';
	
	set_include_path(get_include_path() . PATH_SEPARATOR . $baseDir);
	require_once('MoonSpring/Service/AutoloaderService.php');
	
	$bindings = array(
		'\MoonSpring\DI\IIOCContainer' => '\MoonSpring\DI\FluentIOCContainer'
	);

	new \MoonSpring\Service\AutoLoaderService($baseDir);
	new \MoonSpring\Theme\WPTheme(new \MoonSpring\Service\ServiceManager($bindings));