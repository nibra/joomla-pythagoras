<?php

namespace Joomla\Tests\Unit\Renderer\Mock;

use Joomla\Content\ContentTypeInterface;
use Joomla\Content\Type\Accordion;
use Joomla\Content\Type\Article;
use Joomla\Content\Type\Columns;
use Joomla\Content\Type\DataTable;
use Joomla\Content\Type\DefaultMenu;
use Joomla\Content\Type\Dump;
use Joomla\Content\Type\HorizontalLine;
use Joomla\Content\Type\Icon;
use Joomla\Content\Type\Image;
use Joomla\Content\Type\Link;
use Joomla\Content\Type\OnePager;
use Joomla\Content\Type\OnePagerSection;
use Joomla\Content\Type\Rows;
use Joomla\Content\Type\Slider;
use Joomla\Content\Type\Span;
use Joomla\Content\Type\Tabs;
use Joomla\Content\Type\Teaser;
use Joomla\Content\Type\Tree;

class Renderer extends \Joomla\Renderer\Renderer
{
	public function visitContent(ContentType $content)
	{
		$str = "standard: " . $content->getContents() . "\n";
		$this->output .= $str;

		return strlen($str);
	}

	/**
	 * Render a headline.
	 *
	 * @param   \Joomla\Content\Type\Headline $headline The headline
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitHeadline(\Joomla\Content\Type\Headline $headline)
	{
		return 0;
	}

	/**
	 * Render a compound (block) element
	 *
	 * @param   \Joomla\Content\Type\Compound $compound The compound
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitCompound(\Joomla\Content\Type\Compound $compound)
	{
		return 0;
	}

	/**
	 * Render an attribution to an author
	 *
	 * @param   \Joomla\Content\Type\Attribution $attribution The attribution
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitAttribution(\Joomla\Content\Type\Attribution $attribution)
	{
		return 0;
	}

	/**
	 * Render a paragraph
	 *
	 * @param   \Joomla\Content\Type\Paragraph $paragraph The paragraph
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitParagraph(\Joomla\Content\Type\Paragraph $paragraph)
	{
		return 0;
	}

	/**
	 * Render an image
	 *
	 * @param   Image $image The image
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitImage(Image $image)
	{
		return 0;
	}

	/**
	 * Render an slider
	 *
	 * @param   Slider $slider The slider
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitSlider(Slider $slider)
	{
		return 0;
	}

	/**
	 * Render an accordion
	 *
	 * @param   Accordion $accordion The accordion
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitAccordion(Accordion $accordion)
	{
		return 0;
	}

	/**
	 * Render a tree
	 *
	 * @param   Tree $tree The tree
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitTree(Tree $tree)
	{
		return 0;
	}

	/**
	 * Render tabs
	 *
	 * @param   Tabs $tabs The tabs
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitTabs(Tabs $tabs)
	{
		return 0;
	}

	/**
	 * Dump an item
	 *
	 * @param   Dump $dump The dump
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitDump(ContentTypeInterface $dump)
	{
		return 0;
	}

	/**
	 * Render rows
	 *
	 * @param   Rows $rows The rows
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitRows(Rows $rows)
	{
		return 0;
	}

	/**
	 * Render columns
	 *
	 * @param   Columns $columns The columns
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitColumns(Columns $columns)
	{
		return 0;
	}

	/**
	 * Render an article
	 *
	 * @param   Article $article The article
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitArticle(Article $article)
	{
		return 0;
	}

	/**
	 * Render a teaser
	 *
	 * @param   Teaser $teaser The teaser
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitTeaser(Teaser $teaser)
	{
		return 0;
	}

	/**
	 * Render a defaultMenu
	 *
	 * @param   DefaultMenu $defaultMenu The defaultMenu
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitDefaultMenu(DefaultMenu $defaultMenu)
	{
		return 0;
	}

	/**
	 * Render a data table
	 *
	 * @param   DataTable $dataTable The data table
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitDataTable(DataTable $dataTable)
	{
		return 0;
	}

	/**
	 * Render a span
	 *
	 * @param   Span $span The span
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitSpan(Span $span)
	{
		return 0;
	}

	/**
	 * Render a horizontal line
	 *
	 * @param   HorizontalLine $hr The horizontal line
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitHorizontalLine(HorizontalLine $hr)
	{
		return 0;
	}

	/**
	 * Render an icon
	 *
	 * @param   Icon $icon The icon
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitIcon(Icon $icon)
	{
		return 0;
	}

	/**
	 * Render a link
	 *
	 * @param   Link $link The link
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitLink(Link $link)
	{
		return 0;
	}

	/**
	 * Render an one-pager
	 *
	 * @param   OnePager $onePager The one-pager
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitOnePager(OnePager $onePager)
	{
		return 0;
	}

	/**
	 * Render an one-pager section
	 *
	 * @param   OnePagerSection $onePagerSection The section
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitOnePagerSection(OnePagerSection $onePagerSection)
	{
		return 0;
	}
}
