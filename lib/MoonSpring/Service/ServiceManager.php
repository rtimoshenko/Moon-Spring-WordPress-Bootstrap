<?php namespace MoonSpring\Service;

use MoonSpring\DI\IIOCContainer;

class ServiceManager
{
	protected $iocContainer = null;

	public function __construct(IIOCContainer $iocContainer)
	{
		if (is_null($iocContainer))
			$iocContainer = new FluentIOCContainer();
	}
}