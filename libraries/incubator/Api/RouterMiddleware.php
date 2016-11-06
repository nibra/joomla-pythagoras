<?php
/**
 * Part of the Joomla API Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Api;

use InvalidArgumentException;
use Joomla\Http\MiddlewareInterface;
use Joomla\Router\Router;
use Joomla\Service\Command;
use Joomla\Service\CommandHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class NullCommand extends Command {}
class NullCommandHandler extends CommandHandler {
	public function handle(NullCommand $command)
	{
	}
}

/**
 * Determines the command to be executed.
 *
 * @package  Joomla/PageBuilder
 *
 * @since    __DEPLOY_VERSION__
 */
class RouterMiddleware extends Router implements MiddlewareInterface
{
	public function __construct()
	{
		parent::__construct();

		$this->createRoutes();
	}

	/**
	 * Execute the middleware. Don't call this method directly; it is used by the `Application` internally.
	 *
	 * @internal
	 *
	 * @param   ServerRequestInterface $request  The request object
	 * @param   ResponseInterface      $response The response object
	 * @param   callable               $next     The next middleware handler
	 *
	 * @return  ResponseInterface
	 */
	public function handle(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
	{
		$attributes = $request->getAttributes();

		if (!isset($attributes['command']))
		{
			try
			{
				$path  = preg_replace('~^/.*?api.php/?~', '', $request->getUri()->getPath());
				$route = $this->parseRoute($path);
				$page  = $route['controller'];
				$vars  = $route['vars'];

				$command = new NullCommand;
				$request = $request->withAttribute('command', $command);
				$output = $response->getBody();
				$output->write("<pre>" . print_r($route, true) . "</pre>");
				// @todo Emit afterRouting event
			}
			catch (InvalidArgumentException $e)
			{
				// Do nothing
			}
		}

		return $next($request, $response);
	}

	private function createRoutes()
	{
		$this
			->addRoute('GET', '/:entity', 'foo')
			->addRoute('GET', '/:entity/:id', 'foo', ['id' => '\d+'])
			->addRoute('GET', '/:entity/:id/:relation', 'foo', ['id' => '\d+'])
			->addRoute('PUT', '/:entity', 'foo')
			->addRoute('PUT', '/:entity/:id', 'foo', ['id' => '\d+'])
			->addRoute('POST', '/:entity', 'foo')
			->addRoute('POST', '/:entity/:id', 'foo', ['id' => '\d+'])
			->addRoute('DELETE', '/:entity', 'foo')
			->addRoute('DELETE', '/:entity/:id', 'foo', ['id' => '\d+'])
			->addRoute('DELETE', '/:entity/:id/:relation', 'foo', ['id' => '\d+']);
	}
}
