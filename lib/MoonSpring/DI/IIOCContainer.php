<?php namespace MoonSpring\DI;

interface IIOCContainer
{
	public function bind($abstract, $concrete);
	public function resolve($abstract, $params);
}