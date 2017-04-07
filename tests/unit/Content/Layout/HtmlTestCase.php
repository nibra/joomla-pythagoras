<?php
/**
 * Part of the Joomla Framework Content Package Test Suite
 *
 * @copyright  Copyright (C) 2015 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\Unit\Content\Layout;

class HtmlTestCase extends \PHPUnit_Framework_TestCase
{
	protected function stripComments($html)
	{
		return preg_replace('~\<!--.*?-->~', '', $html);
	}

	protected function assertHtmlEquals($expected, $actual, $message = '')
	{
		$this->assertXmlStringEqualsXmlString($expected, $actual, $message);
	}
}
