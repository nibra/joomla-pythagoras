<?php
/**
 * Part of the Joomla Framework HTTP Package Test Suite
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\Unit\Http;

use Joomla\Cms\ServiceProvider\EventDispatcherServiceProvider;
use Joomla\Cms\ServiceProvider\ExtensionFactoryServiceProvider;
use Joomla\DI\Container;
use Joomla\Event\Dispatcher;
use Joomla\Http\Application;
use Joomla\Http\Middleware\RendererMiddleware;
use Joomla\ORM\Service\StorageServiceProvider;
use Joomla\Renderer\EventDecorator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use UnitTester;
use Zend\Diactoros\ServerRequest;

class RendererCest
{
	public function _before(UnitTester $I)
	{
	}

	public function _after(UnitTester $I)
	{
	}

	public function RendererAddsTheEventDecorator(UnitTester $I)
	{
		$container = new Container();
		$container->set('ConfigDirectory', JPATH_ROOT);
		$container->registerServiceProvider(new StorageServiceProvider, 'repository');
		$container->registerServiceProvider(new EventDispatcherServiceProvider, 'dispatcher');
		$container->registerServiceProvider(new ExtensionFactoryServiceProvider, 'extension_factory');

		$app       = new Application([
			new RendererMiddleware(new Dispatcher(), $container),
			function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($I, $container)
			{
				$renderer = $container->get('Renderer');

				$I->assertEquals(EventDecorator::class, get_class($renderer));

				return $next($request, $response);
			}
		]);

		$app->run(new ServerRequest());
	}
}
