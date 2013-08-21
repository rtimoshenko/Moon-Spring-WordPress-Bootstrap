<?php

// http://php.net/manual/en/language.oop5.autoload.php
class MSAutoLoader 
{
	private $_classPaths = array();

    public function __construct($classPaths)
    {
    	$this->_classPaths = $classPaths;
        spl_autoload_register('MSAutoLoader::load');
    }

    public static function load($class)
    {
        foreach($this->_classPaths as $path)
        {
            $filePath = $path . $class . '.php';
            
            if(file_exists($filePath))
            {
                require_once($filePath);
                break;
            }            
        }

        return $this;
    }
}