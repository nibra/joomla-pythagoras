<?php
/**
 * Part of the Joomla Framework HTTP Package Test Suite
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\Unit\Http;

use Joomla\Component\Content\Command\DisplayCommand;
use Joomla\Http\Application;
use Joomla\Http\Middleware\ApiRouterMiddleware;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use UnitTester;
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Uri;

class ApiRouterCest
{
	public function _before(UnitTester $I)
	{
	}

	public function _after(UnitTester $I)
	{
	}

	public function RecogniseDisplayCommands(UnitTester $I)
	{
		$app = new Application([
			new ApiRouterMiddleware(),
			function (ServerRequestInterface $request, ResponseInterface $response, callable $next) use ($I)
			{
				$commandClass = "Joomla\\Component\\Content\\Command\\DisplayCommand";

				/** @var DisplayCommand $command */
				$command = $request->getAttribute('command');
				$I->assertEquals($commandClass, get_class($command));
				$I->assertEquals('article', $command->entityName);
				$I->assertEquals(1, $command->id);

				return $next($request, $response);
			}
		]);

		$request = new ServerRequest();
		$request = $request->withUri(new Uri('article/1'));

		$app->run($request);
	}
}
