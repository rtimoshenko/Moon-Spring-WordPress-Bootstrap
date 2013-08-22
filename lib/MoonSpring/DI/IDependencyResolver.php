<?php namespace MoonSpring\DI;

interface IDependencyResolver
{
	pubilc function bind($abstract, $concrete);
	pubilc function getBinding($abstract);
	pubilc function tryGetBinding($abstract, $default);
	pubilc function resolve($abstract);
}