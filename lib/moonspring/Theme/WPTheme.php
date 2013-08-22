<?php namespace MoonSpring\Theme;
	//require_once(get_template_directory() . '/lib/config.php');
	//require_once(get_template_directory() . '/lib/utility.php');
	//require_once(get_template_directory() . '/lib/customization.php');
	//require_once(get_template_directory() . '/lib/plugins.php');

/*
    Author: Ronald Timoshenko | ronaldtimoshenko.com
    Date: 2013-08-21
*/


use MoonSpring\Service\IServiceManager;
	
class WPTheme
{

/*
        _whitelist
        _minimal
        _comments

        GetPostsWithParams(array)
        GetMenuByName(string name, int depth)

        // DI
        -MSMetabox: Metabox impelementation
        -MSCustomizer: Overrides default functionality
        -MSViewController: Implements default functionality
        -MSSeo: Seo utils (detect yoast?)
        -MSAssetManager: css,js,images, etc.
    */

    protected $serviceManager = null;
    
    // Dependency injected constructor
    public function __construct(IServiceManager $serviceManager)
    {
        var_dump($serviceManager);
        //$this->assetService = $serviceManager->IAssetService;
        //$this->seoService = $serviceManager->ISEOService;
        exit;
    }
}