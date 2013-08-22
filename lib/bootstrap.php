<?php
	
	use \MoonSpring\Service\AutoLoaderService;
	use \MoonSpring\Service\ServiceManager;
	use \MoonSpring\DI\DependencyResolver;
	use \MoonSpring\DI\FluentIOCContainer;
	use \MoonSpring\Theme\WPTheme;

	error_reporting(E_ALL); // DEBUG
	ini_set('display_errors', '1'); // DEBUG
	set_include_path(get_include_path() . PATH_SEPARATOR . dirname(realpath(__FILE__)));

	require_once('MoonSpring/Service/AutoloaderService.php');

	$loader = new AutoLoaderService();
	$container = new FluentIOCContainer(new DependencyResolver());

	$container->bind('\MoonSpring\DI\IIOCContainer', '\MoonSpring\DI\FluentIOCContainer');

	$theme = new WPTheme(new ServiceManager($container));