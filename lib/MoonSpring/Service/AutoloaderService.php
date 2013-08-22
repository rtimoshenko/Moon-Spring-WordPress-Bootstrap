<?php namespace MoonSpring\Service;

// http://php.net/manual/en/language.oop5.autoload.php
class AutoloaderService
{
	protected $baseDir = '.';
    protected $namespace = 'MoonSpring';
	protected $namespaceSeparator = '\\';

    public function __construct($baseDir)
    {
    	$this->setBaseDir($baseDir);
    	
        spl_autoload_register(array($this, 'load'));
    }
    
    public function setBaseDir($baseDir)
    {
	    $this->baseDir = $baseDir;
    }

    public function load($className)
    {
        // Only load classes in the appropriate namespace
        if(strripos($className, $this->namespace) === false)
        {
            return;
        }
        
        $fileName = $this->normalizeClassName($className);
        
        require_once($this->baseDir . $fileName);
    }

    public function normalizeClassName($className)
    {
        // Get the position of the namespace separator
        $namespacePosition = strripos($className, $this->namespaceSeparator);
        
        // Pull out the namespace and class name
        $namespace = substr($className, 0, $namespacePosition);
        $className = substr($className, $namespacePosition + 1);
        
        // Swap namespace separator for directory separator
        $fileName = str_replace($this->namespaceSeparator, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;

        // Glue the pieces together
        return $fileName . $className . '.php';
    }
}