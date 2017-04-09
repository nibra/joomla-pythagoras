<?php
/**
 * Part of the Joomla Framework Renderer Package Test Suite
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\Unit\Renderer;

use FluentDOM\Element;
use Joomla\Renderer\Exception\NotFoundException;
use PHPUnit_Framework_AssertionFailedError;
use PHPUnit_Framework_ExpectationFailedException;

class HtmlAssertionsTest extends HtmlTestCase
{
	/**
	 * @testdox HtmlTestCase::stripComments() removes XML comments
	 */
	public function testStripComments()
	{
		$html     = '<!-- comment --><p>Text <!-- comment --> Text</p><!-- comment -->';
		$expected = '<p>Text  Text</p>';

		$this->assertEquals($expected, $this->stripComments($html));
	}

	/**
	 * @testdox HtmlTestCase::assertHtmlEquals() ignores formatting
	 */
	public function testAssertHtmlEqualsIgnoresFormatting()
	{
		$html     = "<p>\n    Text\n</p>\n";
		$expected = '<p>Text</p>';

		$this->assertHtmlEquals($expected, $html);
	}

	/**
	 * @testdox HtmlTestCase::assertHtmlEquals() ignores comments
	 */
	public function testAssertHtmlEqualsIgnoresComments()
	{
		$html     = "<p>\n<!-- comment -->Text\n</p>\n";
		$expected = '<p>Text</p>';

		$this->assertHtmlEquals($expected, $html);
	}

	/**
	 * @testdox HtmlTestCase::assertHtmlHasRoot() checks for the tag of the outer element
	 */
	public function testAssertHtmlHasRoot()
	{
		$html = "<p><span>Text</span></p>";

		$this->assertHtmlHasRoot('p', $html);
	}

	/**
	 * @testdox HtmlTestCase::assertHtmlHasRoot() throws PHPUnit_Framework_AssertionFailedError on missing root element
	 */
	public function testAssertHtmlHasRootMissingRoot()
	{
		$html = "<h1>One</h1><h2>Two</h2>";

		try
		{
			$this->assertHtmlHasRoot('h1', $html);
			$this->fail('Expected PHPUnit_Framework_AssertionFailedError was not thrown');
		}
		catch (\Exception $e)
		{
			$this->assertEquals(PHPUnit_Framework_AssertionFailedError::class, get_class($e));
		}
	}

	/**
	 * @testdox HtmlTestCase::assertHtmlRootHasId() checks for a certain id
	 */
	public function testAssertHtmlRootHasId()
	{
		$html = "<div id=\"foo\">content</div>";

		$this->assertHtmlRootHasId('foo', $html);
	}

	/**
	 * @testdox HtmlTestCase::assertHtmlRootHasId() throws PHPUnit_Framework_ExpectationFailedException if id differs
	 */
	public function testAssertHtmlRootHasIdDifferentId()
	{
		$html = "<div id=\"foo\">content</div>";

		try
		{
			$this->assertHtmlRootHasId('bar', $html);
			$this->fail('Expected PHPUnit_Framework_ExpectationFailedException was not thrown');
		}
		catch (\Exception $e)
		{
			$this->assertEquals(PHPUnit_Framework_ExpectationFailedException::class, get_class($e));
		}
	}

	/**
	 * @testdox HtmlTestCase::assertHtmlRootHasId() throws PHPUnit_Framework_ExpectationFailedException if id is missing
	 */
	public function testAssertHtmlRootHasIdNoId()
	{
		$html = "<div>content</div>";

		try
		{
			$this->assertHtmlRootHasId('foo', $html);
			$this->fail('Expected PHPUnit_Framework_ExpectationFailedException was not thrown');
		}
		catch (\Exception $e)
		{
			$this->assertEquals(PHPUnit_Framework_ExpectationFailedException::class, get_class($e));
		}
	}

	/**
	 * @testdox HtmlTestCase::assertHtmlRootHasClass() checks for a certain class
	 */
	public function testAssertHtmlRootHasClass()
	{
		$html = "<div class=\"foo\">content</div>";

		$this->assertHtmlRootHasClass('foo', $html);
	}

	/**
	 * @testdox HtmlTestCase::assertHtmlRootHasClass() finds the class among multiple classes
	 */
	public function testAssertHtmlRootHasClassMultiple()
	{
		$html = "<div class=\"foo bar\">content</div>";

		$this->assertHtmlRootHasClass('foo', $html);
	}

	/**
	 * @testdox HtmlTestCase::assertHtmlRootHasClass() throws PHPUnit_Framework_ExpectationFailedException if class differs
	 */
	public function testAssertHtmlRootHasClassDifferentClass()
	{
		$html = "<div class=\"foo\">content</div>";

		try
		{
			$this->assertHtmlRootHasClass('bar', $html);
			$this->fail('Expected PHPUnit_Framework_ExpectationFailedException was not thrown');
		}
		catch (\Exception $e)
		{
			$this->assertEquals(PHPUnit_Framework_ExpectationFailedException::class, get_class($e));
		}
	}

	/**
	 * @testdox HtmlTestCase::assertHtmlRootHasClass() throws PHPUnit_Framework_ExpectationFailedException if class is missing
	 */
	public function testAssertHtmlRootHasClassNoClass()
	{
		$html = "<div>content</div>";

		try
		{
			$this->assertHtmlRootHasClass('foo', $html);
			$this->fail('Expected PHPUnit_Framework_ExpectationFailedException was not thrown');
		}
		catch (\Exception $e)
		{
			$this->assertEquals(PHPUnit_Framework_ExpectationFailedException::class, get_class($e));
		}
	}

	/**
	 * @testdox HtmlTestCase::getRootElement() returns the root element
	 */
	public function testGetRootElement()
	{
		$html = "<div class=\"foo\">content</div>";

		$element = $this->getRootElement($html);

		$this->assertInstanceOf(Element::class, $element);
		$this->assertEquals('div', $element->nodeName);
	}

	/**
	 * @testdox HtmlTestCase::getRootElement() throws NotFoundException on missing root element
	 */
	public function testGetRootElementMissingRoot()
	{
		$this->expectException(NotFoundException::class);

		$html = "<h1>One</h1><h2>Two</h2>";

		$element = $this->getRootElement($html);
	}
}
