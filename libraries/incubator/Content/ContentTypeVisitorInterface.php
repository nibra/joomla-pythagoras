<?php
/**
 * Part of the Joomla Framework Renderer Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Content;

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
 * Renderer Interface
 *
 * @package  Joomla/Renderer
 *
 * @since    __DEPLOY_VERSION__
 */
interface ContentTypeVisitorInterface
{
	/**
	 * Render a headline.
	 *
	 * @param   Headline $headline The headline
	 *
	 * @return  void
	 */
	public function visitHeadline(Headline $headline);

	/**
	 * Render a compound (block) element
	 *
	 * @param   Compound $compound The compound
	 *
	 * @return  void
	 */
	public function visitCompound(Compound $compound);

	/**
	 * Render an attribution to an author
	 *
	 * @param   Attribution $attribution The attribution
	 *
	 * @return  void
	 */
	public function visitAttribution(Attribution $attribution);

	/**
	 * Render a paragraph
	 *
	 * @param   Paragraph $paragraph The paragraph
	 *
	 * @return  void
	 */
	public function visitParagraph(Paragraph $paragraph);

	/**
	 * Render an image
	 *
	 * @param   Image $image The image
	 *
	 * @return  void
	 */
	public function visitImage(Image $image);

	/**
	 * Render an slider
	 *
	 * @param   Slider $slider The slider
	 *
	 * @return  void
	 */
	public function visitSlider(Slider $slider);

	/**
	 * Render an accordion
	 *
	 * @param   Accordion $accordion The accordion
	 *
	 * @return  void
	 */
	public function visitAccordion(Accordion $accordion);

	/**
	 * Render a tree
	 *
	 * @param   Tree $tree The tree
	 *
	 * @return  void
	 */
	public function visitTree(Tree $tree);

	/**
	 * Render tabs
	 *
	 * @param   Tabs $tabs The tabs
	 *
	 * @return  void
	 */
	public function visitTabs(Tabs $tabs);

	/**
	 * Dump an item
	 *
	 * @param   Dump $dump The dump
	 *
	 * @return  void
	 */
	public function visitDump(Dump $dump);

	/**
	 * Render rows
	 *
	 * @param   Rows $rows The rows
	 *
	 * @return  void
	 */
	public function visitRows(Rows $rows);

	/**
	 * Render columns
	 *
	 * @param   Columns $columns The columns
	 *
	 * @return  void
	 */
	public function visitColumns(Columns $columns);

	/**
	 * Render an article
	 *
	 * @param   Article $article The article
	 *
	 * @return  void
	 */
	public function visitArticle(Article $article);

	/**
	 * Render a teaser
	 *
	 * @param   Teaser $teaser The teaser
	 *
	 * @return  void
	 */
	public function visitTeaser(Teaser $teaser);

	/**
	 * Render a defaultMenu
	 *
	 * @param   DefaultMenu $defaultMenu The defaultMenu
	 *
	 * @return  void
	 */
	public function visitDefaultMenu(DefaultMenu $defaultMenu);

	/**
	 * Render a data table
	 *
	 * @param   DataTable $dataTable The data table
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitDataTable(DataTable $dataTable);

	/**
	 * Render a span
	 *
	 * @param   Span $span The span
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitSpan(Span $span);

	/**
	 * Render a horizontal line
	 *
	 * @param   HorizontalLine $hr The horizontal line
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitHorizontalLine(HorizontalLine $hr);

	/**
	 * Render an icon
	 *
	 * @param   Icon $icon The icon
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitIcon(Icon $icon);

	/**
	 * Render a link
	 *
	 * @param   Link $link The link
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitLink(Link $link);

	/**
	 * Render an one-pager
	 *
	 * @param   OnePager $onePager The one-pager
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitOnePager(OnePager $onePager);

	/**
	 * Render an one-pager section
	 *
	 * @param   OnePagerSection $onePagerSection The section
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitOnePagerSection(OnePagerSection $onePagerSection);
}
