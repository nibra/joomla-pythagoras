<?php
/**
 * Part of the Joomla Framework Renderer Package Test Suite
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\Unit\Renderer;

use Joomla\Renderer\CssAwareImplementation;
use Joomla\Renderer\CssAwareInterface;

class CssAwareTest extends \PHPUnit_Framework_TestCase
{
	/** @var  CssAwareInterface */
	private $renderer;

	public function setUp()
	{
		$this->renderer = new class implements CssAwareInterface
		{
			public $output = "<html><head>head content</head><body>body content</body></html>";

			use CssAwareImplementation;
		};
	}

	public function testRemoteCssIsAddedToHead()
	{
		$this->renderer->addRemoteCss('label', 'CSS');
		$this->renderer->writeCss();

		$this->assertEquals(
			"<html><head>head content<link rel=\"stylesheet\" href=\"CSS\"></head><body>body content</body></html>",
			$this->renderer->output
		);
	}

	public function testRemoteCssIsAddedOncePerLabel()
	{
		$this->renderer->addRemoteCss('label-1', 'Should be overwritten');
		$this->renderer->addRemoteCss('label-1', 'C');
		$this->renderer->addRemoteCss('label-2', 'SS');
		$this->renderer->writeCss();

		$this->assertEquals(
			"<html><head>head content<link rel=\"stylesheet\" href=\"C\"><link rel=\"stylesheet\" href=\"SS\"></head><body>body content</body></html>",
			$this->renderer->output
		);
	}

	public function testEmbeddedCssIsPrefixedAndAddedToHead()
	{
		$this->renderer->embedCss('id', '{}');
		$this->renderer->writeCss();

		$this->assertEquals(
			"<html><head>head content<style>#id {}</style></head><body>body content</body></html>",
			$this->renderer->output
		);
	}

	public function testEmbeddedCssIsAddedAfterRemoteCss()
	{
		$this->renderer->embedCss('id', '{}');
		$this->renderer->addRemoteCss('label', 'CSS');
		$this->renderer->writeCss();

		$this->assertEquals(
			"<html><head>head content<link rel=\"stylesheet\" href=\"CSS\"><style>#id {}</style></head><body>body content</body></html>",
			$this->renderer->output
		);
	}
}
