<?php

/*
    Author: Ronald Timoshenko | ronaldtimoshenko.com
    Date: 2013-08-16
    Memoized and lazy-loaded configuration loader
*/
class MSConfig
{
	//const SEO_TITLE_KEY = 'SEO_title';
    //const SEO_DESCRIPTION_KEY = 'SEO_description';
    
    //const CMS_DISTRIBUTOR_NAME = 'Moon Spring, LLC';
    //const CMS_DISTRIBUTOR_URL = 'http://moonspring.net';

    //const SHOULD_WHITELIST = true;
    //const SHOULD_DISABLE_COMMENTS = true;

    const DEFAULT_MEMOIZATION_KEY = 'core';

    private $_memoizedConfigs = array();

    public function __construct($iniPath)
    {
    	$this->loadConfig($iniPath, DEFAULT_MEMOIZATION_KEY);
    }

    public function loadConfig($iniPath, $memoKey)
    {
    	$config = $this->getStoredConfig($memoKey);

    	if (empty($config))
    	{
			// Parse with sections - http://php.net/manual/en/function.parse-ini-file.php
			$config = $this->storeConfig($memoKey, parse_ini_file($iniPath, true));
		}

		return $config;
    }

    private function storeConfig($memoKey, $configData)
    {
    	$this->_memoizedConfigs[$memoKey] = $configData;

    	return $this->getStoredConfig($memoKey);
    }

    public function getStoredConfig($memoKey)
    {
    	return $this->_memoizedConfigs[$memoKey];
    }
}