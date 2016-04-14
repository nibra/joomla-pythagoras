<?php
use Joomla\DI\ServiceProviderInterface;
use Joomla\DI\Container;

class SimpleServiceProvider implements ServiceProviderInterface
{

	public function register (Container $container, $alias = null)
	{
		$container->set($alias, 'called');
	}
}
