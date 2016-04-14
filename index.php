<?php
/**
 * Part of the Joomla CMS
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

use Joomla\Http\Application;
use Joomla\Http\Middleware\CommandBusMiddleware;
use Joomla\Http\Middleware\ConfigurationMiddleware;
use Joomla\Http\Middleware\RendererMiddleware;
use Joomla\Http\Middleware\ResponseSenderMiddleware;
use Joomla\Http\Middleware\RouterMiddleware;
use Joomla\Http\ServerRequestFactory;
use Joomla\J3Compatibility\Http\Middleware\RouterMiddleware as LegacyRouterMiddleware;
use Joomla\DI\Loader\IniLoader;

require_once 'libraries/vendor/autoload.php';

$root = __DIR__;
$container = new \Joomla\DI\Container();
(new IniLoader($container))->loadFromFile($root . '/config/services.ini');

$app  = new Application(
	[
		new ResponseSenderMiddleware,
		new ConfigurationMiddleware($root, $container),
		new RendererMiddleware($container->get('dispatcher')),
		new RouterMiddleware,
		new LegacyRouterMiddleware,
		new CommandBusMiddleware,
	]
);

$response = $app->run(ServerRequestFactory::fromGlobals());
