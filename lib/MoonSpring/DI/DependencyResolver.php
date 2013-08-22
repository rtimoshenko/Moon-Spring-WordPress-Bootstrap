<?php namespace MoonSpring\DI;

class DependencyResolver implements IDependencyResolver
{
	protected $bindings = array();

	public function bind($abstract, $concrete)
	{
		if (is_null($abstract))
			throw new Exception('Error: $abstract value in bind method cannot be null.');

		if (is_null($concrete))
			throw new Exception('Error: $concrete value in bind method cannot be null.');

		$this->bindings[$abstract] = $concrete;

		return $this;
	}

	public function getBinding($abstract)
	{
		return $this->bindings[$abstract];
	}

	public function tryGetBinding($abstract, $default)
	{
		$binding = $this->getBinding($abstract);

		return (is_null($binding)) ? $default : $binding;
	}

	public function resolve($abstract, $params = array())
	{
		$concrete = $this->tryGetBinding($abstract);
		
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
			$dependencies = $this->resolveInstanceDependenciesWith($params);
			$instance = $reflector->newInstanceArgs($dependencies);
		}

		return $instance;
	}

	protected function resolveInstanceDependenciesWith($params)
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