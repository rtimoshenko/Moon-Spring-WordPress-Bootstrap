<?php namespace MoonSpring\DI;

/*
    Author: Ronald Timoshenko | ronaldtimoshenko.com
    Date: 2013-08-21
    
    Tiny Fluent Inversion of Control (IOC) container
*/
class FluentIOCContainer implements IIOCContainer
{
	protected $resolver = null;

	public function __construct(IDependencyResolver $dependencyResolver)
	{
		$this->resolver = $dependencyResolver;
	}

	public function bind($abstract, $concrete)
	{
		$this->resolver->bind($abstract, $concrete);
		return $this;
	}

	public function resolve($abstract, $params = array())
	{
		$this->resolver->resolve($abstract, $params);
		return $this;
	}
}