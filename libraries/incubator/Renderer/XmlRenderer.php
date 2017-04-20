<?php
/**
 * Part of the Joomla Framework Renderer Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Renderer;

use Joomla\Content\ContentTypeInterface;
use Joomla\Content\Type\Accordion;
use Joomla\Content\Type\Article;
use Joomla\Content\Type\Attribution;
use Joomla\Content\Type\Columns;
use Joomla\Content\Type\Compound;
use Joomla\Content\Type\DataTable;
use Joomla\Content\Type\DefaultMenu;
use Joomla\Content\Type\Dump;
use Joomla\Content\Type\Headline;
use Joomla\Content\Type\HorizontalLine;
use Joomla\Content\Type\Icon;
use Joomla\Content\Type\Image;
use Joomla\Content\Type\Link;
use Joomla\Content\Type\OnePager;
use Joomla\Content\Type\OnePagerSection;
use Joomla\Content\Type\Paragraph;
use Joomla\Content\Type\Rows;
use Joomla\Content\Type\Slider;
use Joomla\Content\Type\Span;
use Joomla\Content\Type\Tabs;
use Joomla\Content\Type\Teaser;
use Joomla\Content\Type\Tree;

/**
 * Class XmlRenderer
 *
 * @package  Joomla/Renderer
 *
 * @since    __DEPLOY_VERSION__
 */
class XmlRenderer extends Renderer
{
	/** @var string The MIME type */
	protected $mediatype = 'application/xml';

	/**
	 * Render a headline.
	 *
	 * @param   Headline $headline The headline
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitHeadline(Headline $headline)
	{
		throw new \LogicException(__METHOD__ . ' is not implemented.');
	}

	/**
	 * Render a compound (block) element
	 *
	 * @param   Compound $compound The compound
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitCompound(Compound $compound)
	{
		throw new \LogicException(__METHOD__ . ' is not implemented.');
	}

	/**
	 * Render an attribution to an author
	 *
	 * @param   Attribution $attribution The attribution
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitAttribution(Attribution $attribution)
	{
		throw new \LogicException(__METHOD__ . ' is not implemented.');
	}

	/**
	 * Render a paragraph
	 *
	 * @param   Paragraph $paragraph The paragraph
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitParagraph(Paragraph $paragraph)
	{
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
	}

	/**
	 * Dump an item
	 *
	 * @param   Dump $dump The dump
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitDump(Dump $dump)
	{
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
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
		throw new \LogicException(__METHOD__ . ' is not implemented.');
	}
}
