<?php

/*
    Author: Ronald Timoshenko | ronaldtimoshenko.com
    Date: 2013-08-21
    
    Super tiny Inversion of Control (IOC) container based on Laravel IOC Container impelementation
	https://github.com/laravel/framework/blob/master/src/Illuminate/Container/Container.php
*/
namespace MoonSpring\IOC\Concrete
{
	class IOCContainer implements \MoonSpring\IOC\IIOCContainer
	{
		private $_bindings = array();
		private $_instances = array();
	
		public function bind($abstract, $concrete, $isSingleton = false)
		{
			if (is_null($abstract))
				throw new Exception('Error: $abstract value in bind method cannot be null.');
	
			if (is_null($concrete))
				throw new Exception('Error: $concrete value in bind method cannot be null.');
	
			$this->_bindings[$abstract] = compact('concrete', 'isSingleton');
		}
	
		public function getBinding($abstract)
		{
			return $this->_bindings[$abstract];
		}
	
		public function isSingleton($abstract)
		{
			$binding = $this->getBinding($abstract);
			return (bool) $binding['isSingleton'];
		}
	
		public function resolve($abstract, $params = array())
		{
			// Item is a singleton, return the current instance
			if (isset($this->_instances[$abstract]))
			{
				return $this->_instances[$abstract];
			}
	
			$instance = $this->makeInstance($abstract, $params);
	
			if ($this->isSingleton($abstract))
			{
				$this->_instances[$abstract] = $instance;
			}
	
			return $instance;
		}
	
		private function makeInstance($abstract, $params = array())
		{
			extract($this->getBinding($abstract));
			
			$reflector = new ReflectionClass($concrete);
			$constructor = $reflector->getConstructor();
	
			// If there isn't a constructor, then there aren't any dependencies
			if (is_null($constructor))
			{
				$instance = new $concrete;
			}
			else if (!empty($params))
			{
				$instance = $reflector->newInstanceArgs($params);
			}
			else
			{
				$dependencies = $this->getDependencies($params);
				$instance = $reflector->newInstanceArgs($dependencies);
			}
	
			return $instance;
		}
	
		private function getDependencies($params)
		{
			$dependencies = array();
	
			foreach ($params as $param) 
			{
				$dependency = $param->getClass();
	
				if (is_null($dependency))
				{
					$dependencies[] = null;
				}
				else
				{
					$dependencies[] = $this->resolve($dependency->name);
				}
	
			}
	
			return (array) $dependencies;
		}
	}
}