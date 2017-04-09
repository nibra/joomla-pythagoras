<?php
/**
 * Part of the Joomla Framework Content Package Test Suite
 *
 * @copyright  Copyright (C) 2015 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\Unit\Renderer;

use FluentDOM\Document;
use Joomla\Renderer\Exception\NotFoundException;

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

		$document = new Document();
		$document->loadHTML($html);
		$document->normalize();
		$html = $document->saveHTML();

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

		$html = (string) $tidy;
		$html = preg_replace('~\>\s+~sm', '>', $html);

		return $html;
	}

	protected function assertHtmlHasRoot($expectedTag, $html)
	{
		try
		{
			$root = $this->getRootElement($html);
			$this->assertEquals($expectedTag, $root->nodeName);
		}
		catch (NotFoundException $e)
		{
			$this->fail($e->getMessage());
		}
	}

	/**
	 * @param $html
	 *
	 * @return \FluentDOM\Element
	 */
	protected function getRootElement($html)
	{
		$document = new Document();
		$document->loadHTML($html);

		$elements = $document->querySelectorAll('body > *');

		if ($elements->length != 1)
		{
			throw new NotFoundException('No root element found');
		}

		return $elements[0];
	}

	protected function assertHtmlRootHasId($expectedId, $html)
	{
		try
		{
			$root = $this->getRootElement($html);
			$this->assertEquals($expectedId, $root->getAttribute('id'));
		}
		catch (NotFoundException $e)
		{
			$this->fail($e->getMessage());
		}
	}

	protected function assertHtmlRootHasClass($expectedClass, $html)
	{
		try
		{
			$root      = $this->getRootElement($html);
			$attribute = $root->getAttribute('class');

			$this->assertContains($expectedClass, explode(' ', $attribute));
		}
		catch (NotFoundException $e)
		{
			$this->fail($e->getMessage());
		}
	}
}
