<?php
/**
 * Part of the Joomla Framework Renderer Package Test Suite
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Tests\Unit\Renderer;

use Joomla\Content\Type\Accordion;
use Joomla\Content\Type\Compound;
use Joomla\Content\Type\Headline;
use Joomla\Content\Type\Paragraph;
use Joomla\Content\Type\Span;
use Joomla\Renderer\HtmlRenderer;
use Joomla\Renderer\LayoutFactory;

class LayoutTestCases extends HtmlTestCase
{
	/**
	 * @var string
	 */
	protected $layoutPath;

	/**
	 * @var LayoutFactory
	 */
	protected $layoutFactory;

	public function setUp()
	{
		$this->layoutFactory = new LayoutFactory([$this->layoutPath]);
	}

	/**
	 * @testdox Accordion: Enclosed in a div with the given id and an optional class
	 */
	public function testAccordion()
	{
		/** @var HtmlRenderer $renderer */
		$renderer  = $this->getMockBuilder(HtmlRenderer::class)->getMock();
		$accordion = new Accordion('Accordion Title', 'accordion-id', ['class' => 'special']);

		for ($i = 0; $i < 3; $i++)
		{
			$compound       = new Compound('div', 'Title ' . $i, null, []);
			$compound->html = $this->layoutFactory->createLayout('Compound', $compound)->render($renderer);
			$accordion->addChild($compound);
		}

		$html = $this
			->layoutFactory
			->createLayout('Accordion', $accordion)
			->render($renderer);

		$this->assertHtmlHasRoot('div', $html);
		$this->assertHtmlRootHasId('accordion-id', $html);
		$this->assertHtmlRootHasClass('special', $html);
	}

	public function testArticle()
	{
		$this->markTestIncomplete('Not implemented');
	}

	public function testAttribution()
	{
		$this->markTestIncomplete('Not implemented');
	}

	public function testColumns()
	{
		$this->markTestIncomplete('Not implemented');
	}

	public function testCompound()
	{
		$this->markTestIncomplete('Not implemented');
	}

	public function testDefaultMenu()
	{
		$this->markTestIncomplete('Not implemented');
	}

	/**
	 * @testdox Headline: Level can be set explicitly
	 */
	public function testHeadlineLevelIsUsed()
	{
		/** @var HtmlRenderer $renderer */
		$renderer = $this->getMockBuilder(HtmlRenderer::class)->getMock();
		$content  = new Headline('Hello World!', 2);
		$id       = $content->getId();
		$layout   = $this->layoutFactory->createLayout('Headline', $content);

		$this->assertHtmlEquals("<h2 id=\"{$id}\">Hello World!</h2>", $layout->render($renderer));
	}

	/**
	 * @testdox Headline: Level defaults to 1
	 */
	public function testHeadlineLevelDefaultsTo1()
	{
		/** @var HtmlRenderer $renderer */
		$renderer = $this->getMockBuilder(HtmlRenderer::class)->getMock();
		$content  = new Headline('Hello World!');
		$id       = $content->getId();
		$layout   = $this->layoutFactory->createLayout('Headline', $content);

		$this->assertHtmlEquals("<h1 id=\"{$id}\">Hello World!</h1>", $layout->render($renderer));
	}

	/**
	 * @testdox Headline: A class attribute can be provided
	 */
	public function testHeadlineClassOptionIsUsed()
	{
		/** @var HtmlRenderer $renderer */
		$renderer = $this->getMockBuilder(HtmlRenderer::class)->getMock();
		$content  = new Headline('Hello World!', 2, ['class' => 'title']);
		$id       = $content->getId();
		$layout   = $this->layoutFactory->createLayout('Headline', $content);

		$this->assertHtmlEquals("<h2 id=\"{$id}\" class=\"title\">Hello World!</h2>", $layout->render($renderer));
	}

	public function testIcon()
	{
		$this->markTestIncomplete('Not implemented');
	}

	public function testImage()
	{
		$this->markTestIncomplete('Not implemented');
	}

	public function testLink()
	{
		$this->markTestIncomplete('Not implemented');
	}

	public function testOnepager()
	{
		$this->markTestIncomplete('Not implemented');
	}

	public function testOnepagerSection()
	{
		$this->markTestIncomplete('Not implemented');
	}

	/**
	 * @testdox Paragraph: Copy text is enclosed in a p-tag
	 */
	public function testParagraph()
	{
		/** @var HtmlRenderer $renderer */
		$renderer = $this->getMockBuilder(HtmlRenderer::class)->getMock();
		$content  = new Paragraph('Copy Text');
		$id       = $content->getId();
		$layout   = $this->layoutFactory->createLayout('Paragraph', $content);

		$this->assertHtmlEquals("<p id=\"{$id}\">Copy Text</p>", $layout->render($renderer));
	}

	/**
	 * @testdox Paragraph: Copy text can be emphasised
	 */
	public function testParagraphEmphasised()
	{
		/** @var HtmlRenderer $renderer */
		$renderer = $this->getMockBuilder(HtmlRenderer::class)->getMock();
		$content  = new Paragraph('Copy Text', Paragraph::EMPHASISED);
		$id       = $content->getId();
		$layout   = $this->layoutFactory->createLayout('Paragraph', $content);

		$this->assertHtmlEquals("<p id=\"{$id}\"><em>Copy Text</em></p>", $layout->render($renderer));
	}

	/**
	 * @testdox Paragraph: A class attribute can be provided
	 */
	public function testParagraphClass()
	{
		/** @var HtmlRenderer $renderer */
		$renderer = $this->getMockBuilder(HtmlRenderer::class)->getMock();
		$content  = new Paragraph('Copy Text', Paragraph::PLAIN, ['class' => 'special']);
		$id       = $content->getId();
		$layout   = $this->layoutFactory->createLayout('Paragraph', $content);

		$this->assertHtmlEquals("<p id=\"{$id}\" class=\"special\">Copy Text</p>", $layout->render($renderer));
	}

	public function testRows()
	{
		$this->markTestIncomplete('Not implemented');
	}

	public function testSlider()
	{
		$this->markTestIncomplete('Not implemented');
	}

	/**
	 * @testdox Span: Enclosed in a span with an optional id and an optional class
	 */
	public function testSpan()
	{
		/** @var HtmlRenderer $renderer */
		$renderer = $this->getMockBuilder(HtmlRenderer::class)->getMock();
		$content  = new Span('Text', ['class' => 'special']);
		$id       = $content->getId();
		$layout   = $this->layoutFactory->createLayout('Span', $content);

		$this->assertHtmlEquals("<span id=\"{$id}\" class=\"special\">Text</span>", $layout->render($renderer));
	}

	public function testTabs()
	{
		$this->markTestIncomplete('Not implemented');
	}

	public function testTeaser()
	{
		$this->markTestIncomplete('Not implemented');
	}

	public function testTree()
	{
		$this->markTestIncomplete('Not implemented');
	}
}
