<?php

namespace MoonSpring\IOC
{
	interface IIOCContainer
	{
		public function bind($abstract, $concrete, $isSingleton);
		public function getBinding($abstract);
		public function isSingleton($abstract);
		public function resolve($abstract, $params);
	}
}