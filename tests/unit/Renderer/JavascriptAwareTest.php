<?php
/**
 * Part of the Joomla Framework Renderer Package Test Suite
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\Unit\Renderer;

use Joomla\Renderer\JavascriptAwareImplementation;
use Joomla\Renderer\JavascriptAwareInterface;

class JavascriptAwareTest extends \PHPUnit_Framework_TestCase
{
	/** @var  JavascriptAwareInterface */
	private $renderer;

	public function setUp()
	{
		$this->renderer = new class implements JavascriptAwareInterface {
			public $output = "<html><head>head content</head><body>body content</body></html>";

			use JavascriptAwareImplementation;
		};
	}

	public function testRemoteJavascriptIsAddedToHead()
	{
		$this->renderer->addRemoteJavascript('label', 'JS');
		$this->renderer->writeJavascript();

		$this->assertEquals(
			"<html><head>head content<script src=\"JS\"></script></head><body>body content</body></html>",
			$this->renderer->output
		);
	}

	public function testRemoteJavascriptIsAddedOncePerLabel()
	{
		$this->renderer->addRemoteJavascript('label-1', 'Should be overwritten');
		$this->renderer->addRemoteJavascript('label-1', 'J');
		$this->renderer->addRemoteJavascript('label-2', 'S');
		$this->renderer->writeJavascript();

		$this->assertEquals(
			"<html><head>head content<script src=\"J\"></script><script src=\"S\"></script></head><body>body content</body></html>",
			$this->renderer->output
		);
	}

	public function testEmbeddedJavascriptIsAddedToBody()
	{
		$this->renderer->embedJavascript('label', 'JS');
		$this->renderer->writeJavascript();

		$this->assertEquals(
			"<html><head>head content</head><body>body content<script>JS</script></body></html>",
			$this->renderer->output
		);
	}

	public function testEmbeddedJavascriptIsAddedOncePerLabel()
	{
		$this->renderer->embedJavascript('label-1', 'Should be overwritten');
		$this->renderer->embedJavascript('label-1', 'J');
		$this->renderer->embedJavascript('label-2', 'S');
		$this->renderer->writeJavascript();

		$this->assertEquals(
			"<html><head>head content</head><body>body content<script>J\nS</script></body></html>",
			$this->renderer->output
		);
	}
}
