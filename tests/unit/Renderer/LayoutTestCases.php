<?php
/**
 * Part of the Joomla Framework Renderer Package Test Suite
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\Unit\Renderer;

use Joomla\Content\CompoundTypeInterface;
use Joomla\Content\Type\Accordion;
use Joomla\Content\Type\Columns;
use Joomla\Content\Type\Compound;
use Joomla\Content\Type\Headline;
use Joomla\Content\Type\Paragraph;
use Joomla\Content\Type\Rows;
use Joomla\Content\Type\Slider;
use Joomla\Content\Type\Span;
use Joomla\Content\Type\Tabs;
use Joomla\DI\Container;
use Joomla\Renderer\HtmlRenderer;

class LayoutTestCases extends HtmlTestCase
{
	/** @var string Must be set accordingly in child classes*/
	protected $layoutPath = null;

	/** @var  HtmlRenderer */
	protected $renderer;

	public function setUp()
	{
		$this->renderer = new HtmlRenderer([], new Container());
		$this->renderer->setTemplate(__DIR__ . '/fixtures/template');
	}

	/**
	 * @testdox Accordion: Enclosed in a div with the given id and an optional class
	 */
	public function testAccordion()
	{
		$content = new Accordion('Accordion Title', 'accordion-id', ['class' => 'special']);

		$this->addChildren($content);

		$content->accept($this->renderer);
		$html = (string) $this->renderer;

		$this->assertHtmlHasRoot('div', $html);
		$this->assertHtmlRootHasId('accordion-id', $html);
		$this->assertHtmlRootHasClass('special', $html);
	}

	/**
	 * @param CompoundTypeInterface $content
	 */
	protected function addChildren($content)
	{
		for ($i = 0; $i < 3; $i++)
		{
			$compound = new Compound('div', 'Title ' . $i, null, []);
			$content->addChild($compound);
		}
	}

	/**
	 * @testdox Columns: Enclosed in a div with the given id and an optional class
	 */
	public function testColumns()
	{
		$content = new Columns('Columns Title', 'columns-id', ['class' => 'special']);
		$this->addChildren($content);

		$content->accept($this->renderer);
		$html = (string) $this->renderer;

		$this->assertHtmlHasRoot('div', $html);
		$this->assertHtmlRootHasId('columns-id', $html);
		$this->assertHtmlRootHasClass('special', $html);
	}

	/**
	 * @testdox Compound: Enclosed in the given tag with the given id and an optional class
	 */
	public function testCompound()
	{
		$content = new Compound('pre', 'Ttile', 'compound-id', ['class' => 'special']);
		$this->addChildren($content);

		$content->accept($this->renderer);
		$html = (string) $this->renderer;

		$this->assertHtmlHasRoot('pre', $html);
		$this->assertHtmlRootHasId('compound-id', $html);
		$this->assertHtmlRootHasClass('special', $html);
	}

	/**
	 * @testdox Headline: Level can be set explicitly
	 */
	public function testHeadlineLevelIsUsed()
	{
		$content = new Headline('Hello World!', 2);
		$id      = $content->getId();

		$content->accept($this->renderer);
		$html = (string) $this->renderer;

		$this->assertHtmlEquals("<h2 id=\"{$id}\">Hello World!</h2>", $html);
	}

	/**
	 * @testdox Headline: Level defaults to 1
	 */
	public function testHeadlineLevelDefaultsTo1()
	{
		$content = new Headline('Hello World!');
		$id      = $content->getId();

		$content->accept($this->renderer);
		$html = (string) $this->renderer;

		$this->assertHtmlEquals("<h1 id=\"{$id}\">Hello World!</h1>", $html);
	}

	/**
	 * @testdox Headline: A class attribute can be provided
	 */
	public function testHeadlineClassOptionIsUsed()
	{
		$content = new Headline('Hello World!', 2, ['class' => 'title']);
		$id      = $content->getId();

		$content->accept($this->renderer);
		$html = (string) $this->renderer;

		$this->assertHtmlEquals("<h2 id=\"{$id}\" class=\"title\">Hello World!</h2>", $html);
	}

	/**
	 * @testdox Paragraph: Copy text is enclosed in a p-tag
	 */
	public function testParagraph()
	{
		$content = new Paragraph('Copy Text');
		$id      = $content->getId();

		$content->accept($this->renderer);
		$html = (string) $this->renderer;

		$this->assertHtmlEquals("<p id=\"{$id}\">Copy Text</p>", $html);
	}

	/**
	 * @testdox Paragraph: Copy text can be emphasised
	 */
	public function testParagraphEmphasised()
	{
		$content = new Paragraph('Copy Text', Paragraph::EMPHASISED);
		$id      = $content->getId();

		$content->accept($this->renderer);
		$html = (string) $this->renderer;

		$this->assertHtmlEquals("<p id=\"{$id}\"><em>Copy Text</em></p>", $html);
	}

	/**
	 * @testdox Paragraph: A class attribute can be provided
	 */
	public function testParagraphClass()
	{
		$content = new Paragraph('Copy Text', Paragraph::PLAIN, ['class' => 'special']);
		$id      = $content->getId();

		$content->accept($this->renderer);
		$html = (string) $this->renderer;

		$this->assertHtmlEquals("<p id=\"{$id}\" class=\"special\">Copy Text</p>", $html);
	}

	/**
	 * @testdox Rows: Enclosed in a div with the given id and an optional class
	 */
	public function testRows()
	{
		$content = new Rows('Rows Title', 'rows-id', ['class' => 'special']);
		$this->addChildren($content);

		$content->accept($this->renderer);
		$html = (string) $this->renderer;

		$this->assertHtmlHasRoot('div', $html);
		$this->assertHtmlRootHasId('rows-id', $html);
		$this->assertHtmlRootHasClass('special', $html);
	}

	/**
	 * @testdox Slider: Enclosed in a div with the given id and an optional class
	 */
	public function testSlider()
	{
		$content = new Slider('Slider Title', 'slider-id', ['class' => 'special']);
		$this->addChildren($content);

		$content->accept($this->renderer);
		$html = (string) $this->renderer;

		$this->assertHtmlHasRoot('div', $html);
		$this->assertHtmlRootHasId('slider-id', $html);
		$this->assertHtmlRootHasClass('special', $html);
	}

	/**
	 * @testdox Span: Enclosed in a span with an optional id and an optional class
	 */
	public function testSpan()
	{
		$content = new Span('Text', ['class' => 'special']);
		$id      = $content->getId();

		$content->accept($this->renderer);
		$html = (string) $this->renderer;

		$this->assertHtmlEquals("<span id=\"{$id}\" class=\"special\">Text</span>", $html);
	}

	/**
	 * @testdox Tabs: Enclosed in a div with the given id and an optional class
	 */
	public function testTabs()
	{
		$content = new Tabs('Tabs Title', 'tabs-id', ['class' => 'special']);
		$this->addChildren($content);

		$content->accept($this->renderer);
		$html = (string) $this->renderer;

		$this->assertHtmlHasRoot('div', $html);
		$this->assertHtmlRootHasId('tabs-id', $html);
		$this->assertHtmlRootHasClass('special', $html);
	}
}
