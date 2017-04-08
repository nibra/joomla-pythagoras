<?php
/**
 * Part of the Joomla Framework Content Package Test Suite
 *
 * @copyright  Copyright (C) 2015 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\Unit\Renderer;

use DOMDocument;

class HtmlTestCase extends \PHPUnit_Framework_TestCase
{
	protected function stripComments($html)
	{
		return preg_replace('~\<!--.*?-->~', '', $html);
	}

	protected function assertHtmlEquals($expected, $actual, $message = '')
	{
		$this->assertXmlStringEqualsXmlString($this->normalise($expected), $this->normalise($actual), $message);
	}

	private function normalise($html)
	{
		if (!class_exists(\Tidy::class))
		{
			$this->markTestSkipped('Tidy is not available');

			return $html;
		}

		$html = str_replace("><", ">\n<", $html);

		$xml = new DOMDocument('1.0', 'UTF-8');
		$xml->loadHTML($html);
		$xml->normalize();
		$html = $xml->saveHTML();

		$tidy = new \Tidy();
		$tidy->parseString($html, [
			'markup'          => true,
			'sort-attributes' => 'alpha',
			'wrap'            => 0,
			'force-output'    => true,
			'show-body-only'  => true,
			'output-html'     => true,
		]);
		$tidy->cleanRepair();

		return (string) $tidy;
	}
}
