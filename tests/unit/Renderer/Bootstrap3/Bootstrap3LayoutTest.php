<?php
/**
 * Part of the Joomla Framework Renderer Package Test Suite
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\Unit\Renderer;

use Joomla\Content\Type\OnePagerSection;

class Bootstrap3LayoutTest extends LayoutTestCases
{
	/**
	 * @var string
	 */
	protected $layoutPath = JPATH_ROOT . '/layouts/bootstrap-3';

	/**
	 * @testdox OnePagerSection: Enclosed in the given tag with the given id
	 */
	public function testOnePagerSection()
	{
		$content = new OnePagerSection('pre', 'Title', 'section-id', ['class' => 'special']);
		$this->addChildren($content);

		$content->accept($this->renderer);
		$html = (string) $this->renderer;

		$this->assertHtmlHasRoot('pre', $html);
		$this->assertHtmlRootHasId('section-id', $html);
		$this->assertContains("<div class=\"container special\">", $html);
	}
}
