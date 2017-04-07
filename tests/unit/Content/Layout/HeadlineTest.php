<?php
/**
 * Part of the Joomla Framework Content Package Test Suite
 *
 * @copyright  Copyright (C) 2015 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\Unit\Content\Layout;

use Joomla\Content\Type\Headline;
use Joomla\Renderer\LayoutWrapper;

class HeadlineTest extends HtmlTestCase
{
	public function testLevelDefaultsTo1()
	{
		$content = new Headline('Hello World!');
		$id      = $content->getId();
		$paths   = ['layouts/bootstrap-3'];
		$wrapper = new LayoutWrapper('Headline', $content, $paths);

		$result = $wrapper->render();
		$this->assertHtmlEquals("<h1 id=\"{$id}\">Hello World!</h1>", $result);
	}

	public function testLevelIsUsed()
	{
		$content = new Headline('Hello World!', 2);
		$id      = $content->getId();
		$paths   = ['layouts/bootstrap-3'];
		$wrapper = new LayoutWrapper('Headline', $content, $paths);

		$result = $wrapper->render();
		$this->assertHtmlEquals("<h2 id=\"{$id}\">Hello World!</h2>", $result);
	}

	public function testClassOptionIsUsed()
	{
		$content = new Headline('Hello World!', 2, ['class' => 'title']);
		$id      = $content->getId();
		$paths   = ['layouts/bootstrap-3'];
		$wrapper = new LayoutWrapper('Headline', $content, $paths);

		$result = $wrapper->render();
		$this->assertHtmlEquals("<h2 id=\"{$id}\" class=\"title\">Hello World!</h2>", $result);
	}
}
