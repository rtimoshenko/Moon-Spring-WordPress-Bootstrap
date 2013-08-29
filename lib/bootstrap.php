<?php
	
	use \MoonSpring\Service\AutoLoaderService;
	//use \MoonSpring\Service\ServiceManager;
	use \MoonSpring\DI\DependencyResolver;
	use \MoonSpring\DI\FluentIOCContainer;
	//use \MoonSpring\Theme\WPTheme;

	error_reporting(E_ALL); // DEBUG
	ini_set('display_errors', '1'); // DEBUG
	set_include_path(get_include_path() . PATH_SEPARATOR . dirname(realpath(__FILE__)));

	require_once('MoonSpring/Service/AutoloaderService.php');

	$loader = new AutoLoaderService();
	$container = new FluentIOCContainer(new DependencyResolver());

	$container->bind('\MoonSpring\Service\IServiceManager', '\MoonSpring\Service\ServiceManager');

	// http://en.wikipedia.org/wiki/Hollywood_Principle
	// "Don't call us, we'll call you" - don't inject container, have the container make the call
	$theme = $container->resolve('\MoonSpring\Theme\WPTheme');