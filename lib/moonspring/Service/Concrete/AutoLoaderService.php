<?php

namespace MoonSpring\Service\Concrete
{

	// http://php.net/manual/en/language.oop5.autoload.php
	class AutoLoaderService
	{
		private $_baseDir = '.';
		private $_namespaceSeparator = '\\';
	
	    public function __construct($baseDir)
	    {
	    	$this->setBaseDir($baseDir);
	    	
	        spl_autoload_register(array($this, 'load'));
	    }
	    
	    public function setBaseDir($baseDir)
	    {
		    $this->_baseDir = $baseDir;
	    }
	
	    public function load($className)
	    {
	    	// Get the position of the namespace separator
			$namespacePosition = strripos($className, $this->_namespaceSeparator);
			
			// Pull out the namespace and class name
            $namespace = substr($className, 0, $namespacePosition);
            $className = substr($className, $namespacePosition + 1);
            
            // Swap namespace separator for directory separator
            $fileName = str_replace($this->_namespaceSeparator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            
            // Put the pieces together
	        $filePath = $this->_baseDir . $fileName . $className . '.php';
	        
	        require($filePath);
	    }
	}
}