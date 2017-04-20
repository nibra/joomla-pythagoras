<?php
/**
 * Part of the Joomla Framework Renderer Package Test Suite
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\Unit\Renderer;

use Joomla\Content\Type\Headline;
use Joomla\Renderer\Exception\NotFoundException;
use Joomla\Renderer\HtmlRenderer;
use Joomla\Renderer\LayoutWrapper;

class LayoutTest extends \PHPUnit_Framework_TestCase
{
	public function testLayoutWrapperEncapsulatesLayoutFiles()
	{
		/** @var HtmlRenderer $renderer */
		$renderer = $this->getMockBuilder(HtmlRenderer::class)->getMock();
		$content  = new Headline('Hello World!');
		$paths    = ['tests/unit/Renderer/fixtures'];
		$wrapper  = new LayoutWrapper('Headline', $content, $paths);

		$result = $wrapper->render($renderer);
		$this->assertRegExp('~\<h1[^>]*>Hello World!</h1>~sm', $result);
	}

	public function testLayoutWrapperThrowsException()
	{
		$this->expectException(NotFoundException::class);

		$content = new Headline('Hello World!');
		$paths   = ['tests/unit/Content/Layout/fixtures'];
		$wrapper = new LayoutWrapper('non-existent', $content, $paths);
	}
}
