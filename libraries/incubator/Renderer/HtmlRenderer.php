<?php
/**
 * Part of the Joomla Framework Renderer Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Renderer;

use Joomla\Cms\Entity\Menu;
use Joomla\Content\CompoundTypeInterface;
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
use Joomla\ORM\Entity\EntityBuilder;
use Joomla\ORM\Operator;
use Joomla\ORM\Repository\Repository;
use Joomla\PageBuilder\Entity\Layout;
use Joomla\PageBuilder\Entity\Page;
use Joomla\Renderer\Exception\NotFoundException;
use Joomla\Tests\Unit\DumpTrait;

/**
 * Class HtmlRenderer
 *
 * @package  Joomla/Renderer
 *
 * @since    __DEPLOY_VERSION__
 */
class HtmlRenderer extends Renderer implements JavascriptAwareInterface, CssAwareInterface
{
	/** @var string The MIME type */
	protected $mediatype = 'text/html';

	/** @var string  Template directory */
	protected $template;

	/** @var string  Layout directory */
	protected $layoutDirectory = 'bootstrap-3';

	/** @var  ScriptStrategyInterface */
	private $clientScript;

	/** @var  LayoutFactory */
	private $layoutFactory;

	use DumpTrait;
	use JavascriptAwareImplementation;
	use CssAwareImplementation;

	/**
	 * @param   ScriptStrategyInterface $strategy The scripting strategy (library) to use
	 *
	 * @return  void
	 */
	public function setScriptStrategy(ScriptStrategyInterface $strategy)
	{
		$this->clientScript = $strategy;
	}

	/**
	 * Sets the template
	 *
	 * @param   string $template The template
	 *
	 * @return  void
	 */
	public function setTemplate($template)
	{
		$this->template      = $template;
		$this->layoutFactory = new LayoutFactory([
			$this->template . '/layouts',
			'layouts/' . $this->layoutDirectory,
		]);
	}

	/**
	 * Render an accordion
	 *
	 * @param   Accordion $accordion The accordion
	 *
	 * @return  void
	 */
	public function visitAccordion(Accordion $accordion)
	{
		$accordion->setId('accordion-' . spl_object_hash($accordion));

		$this->preRenderChildElements($accordion);

		$this->applyLayout('Accordion', $accordion);
	}

	/**
	 * Render an article
	 *
	 * @param   Article $article The article
	 *
	 * @return  void
	 */
	public function visitArticle(Article $article)
	{
		$this->applyLayout('Article', $article);
	}

	/**
	 * Render an attribution to an author
	 *
	 * @param   Attribution $attribution The attribution
	 *
	 * @return  void
	 */
	public function visitAttribution(Attribution $attribution)
	{
		$this->applyLayout('Attribution', $attribution);
	}

	/**
	 * Render columns
	 *
	 * @param   Columns $columns The columns
	 *
	 * @return  void
	 */
	public function visitColumns(Columns $columns)
	{
		$this->preRenderChildElements($columns);

		$this->applyLayout('Columns', $columns);
	}

	/**
	 * Render a compound (block) element
	 *
	 * @param   Compound $compound The compound
	 *
	 * @return  void
	 */
	public function visitCompound(Compound $compound)
	{
		$this->applyLayout('Compound', $compound);
	}

	/**
	 * Render a data table
	 *
	 * @param   DataTable $dataTable The data table
	 *
	 * @return  void
	 */
	public function visitDataTable(DataTable $dataTable)
	{
		$items = $dataTable->getChildren();

		/** @var EntityBuilder $entityBuilder */
		$entityBuilder = $this->container->get('Repository')->getEntityBuilder();

		$params           = $dataTable->getParameters();
		$params['entity'] = $entityBuilder->getMeta(get_class($items[0]->item));
		$dataTable->setParameters($params);

		$this->applyLayout('DataTable', $dataTable);
	}

	/**
	 * Render a defaultMenu
	 *
	 * @param   DefaultMenu $defaultMenu The defaultMenu
	 *
	 * @return  void
	 */
	public function visitDefaultMenu(DefaultMenu $defaultMenu)
	{
		if ($defaultMenu->item instanceof Page)
		{
			$defaultMenu->item = $this->convertPageTreeToMenu($defaultMenu->item);
		}

		$this->applyLayout('DefaultMenu', $defaultMenu);
	}

	/**
	 * @param   Page $page The page
	 *
	 * @return  Menu
	 */
	private function convertPageTreeToMenu($page)
	{
		$menu = new Menu(
			$page->title,
			$this->expandUrl($page->url, $page)
		);

		foreach ($page->children->getAll() as $child)
		{
			$menu->add($this->convertPageTreeToMenu($child));
		}

		return $menu;
	}

	/**
	 * Dump an item
	 *
	 * @param   Dump $dump The dump
	 *
	 * @return  void
	 */
	public function visitDump(Dump $dump)
	{
		$this->write('<pre>' . $this->dumpEntity($dump->item) . '</pre>');
	}

	/**
	 * Render a headline.
	 *
	 * @param   Headline $headline The headline
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitHeadline(Headline $headline)
	{
		return $this->applyLayout('Headline', $headline);
	}

	/**
	 * Apply a layout
	 *
	 * @param   string                      $contentType The filename of the layout file
	 * @param   object|ContentTypeInterface $content     The content
	 *
	 * @return  integer
	 */
	private function applyLayout($contentType, $content)
	{
		return $this->layoutFactory->createLayout($contentType, $content)->render($this);
	}

	/**
	 * Render a horizontal line.
	 *
	 * @param   HorizontalLine $headline The horizontal line
	 *
	 * @return  void
	 */
	public function visitHorizontalLine(HorizontalLine $headline)
	{
		$this->write("<hr>\n");
	}

	/**
	 * Render an icon
	 *
	 * @param   Icon $icon The icon
	 *
	 * @return  void
	 */
	public function visitIcon(Icon $icon)
	{
		$this->applyLayout('Icon', $icon);
	}

	/**
	 * Render an image
	 *
	 * @param   Image $image The image
	 *
	 * @return  void
	 */
	public function visitImage(Image $image)
	{
		$this->applyLayout('Image', $image);
	}

	/**
	 * Render a link
	 *
	 * @param Link $link
	 *
	 * @return  void
	 */
	public function visitLink(Link $link)
	{
		$this->applyLayout('Link', $link);
	}

	/**
	 * Render an OnePager
	 *
	 * @param   OnePager $page The page
	 *
	 * @return  void
	 */
	public function visitOnePager(OnePager $page)
	{
		$this->preRenderChildElements($page);

		$this->applyLayout('Onepager', $page);
	}

	/**
	 * Render an OnePager section
	 *
	 * @param   OnePagerSection $section The page
	 *
	 * @return  void
	 */
	public function visitOnePagerSection(OnePagerSection $section)
	{
		$this->preRenderChildElements($section);

		$this->applyLayout('OnepagerSection', $section);
	}

	/**
	 * Render a paragraph
	 *
	 * @param   Paragraph $paragraph The paragraph
	 *
	 * @return  void
	 */
	public function visitParagraph(Paragraph $paragraph)
	{
		$this->applyLayout('Paragraph', $paragraph);
	}

	/**
	 * Render rows
	 *
	 * @param   Rows $rows The rows
	 *
	 * @return  void
	 */
	public function visitRows(Rows $rows)
	{
		$this->preRenderChildElements($rows);

		$this->applyLayout('Rows', $rows);
	}

	/**
	 * Render an slider
	 *
	 * @param   Slider $slider The slider
	 *
	 * @return  void
	 */
	public function visitSlider(Slider $slider)
	{
		$slider->setId('slider-' . spl_object_hash($slider));

		$this->preRenderChildElements($slider);

		$this->applyLayout('Slider', $slider);
	}

	/**
	 * @param   ContentTypeInterface $content The content element
	 *
	 * @return  void
	 */
	private function preRenderChildElements(ContentTypeInterface $content)
	{
		if (!($content instanceof CompoundTypeInterface))
		{
			return;
		}

		$stash = $this->output;

		foreach ($content->getChildren() as $key => $item)
		{
			$this->output = '';
			$item->accept($this);
			$item->html = $this->output;
		}

		$this->output = $stash;
	}

	/**
	 * Render a span element
	 *
	 * @param   Span $span The text
	 *
	 * @return  void
	 */
	public function visitSpan(Span $span)
	{
		$this->applyLayout('Span', $span);
	}

	/**
	 * Render tabs
	 *
	 * @param   Tabs $tabs The tabs
	 *
	 * @return  void
	 */
	public function visitTabs(Tabs $tabs)
	{
		$tabs->setId('tabs-' . spl_object_hash($tabs));

		$this->preRenderChildElements($tabs);

		$this->applyLayout('Tabs', $tabs);
	}

	/**
	 * Render a teaser
	 *
	 * @param   Teaser $teaser The teaser
	 *
	 * @return  void
	 */
	public function visitTeaser(Teaser $teaser)
	{
		$teaser->url = $this->getFullUrl($teaser->article);

		$this->applyLayout('Teaser', $teaser);
	}

	/**
	 * @param   object $object The content object
	 *
	 * @return  string
	 */
	private function getFullUrl($object)
	{
		/** @var Repository $repository */
		$repository   = $this->container->get('Repository')->forEntity('Content');
		$entityType   = explode('\\', get_class($object));
		$entityType   = array_pop($entityType);
		$contentItems = $repository->findAll()->with('component', Operator::EQUAL, $entityType)->getItems();

		$candidates = [];

		foreach ($contentItems as $item)
		{
			if (!empty($item->selection) && !empty($item->selection->alias))
			{
				$candidates[] = $this->expandUrl($object->alias, $item->page);
			}
		}

		if (empty($candidates))
		{
			throw new NotFoundException('Unable to find a URL');
		}

		if (count($candidates) > 1)
		{
			// @todo Warn about ambiguosity
		}

		return $candidates[0];
	}

	/**
	 * @param   string $url  The URL
	 * @param   Page   $page The page
	 *
	 * @return string
	 */
	private function expandUrl($url, $page)
	{
		if (empty($url))
		{
			return '/index.php';
		}

		while ($url[0] != '/' && !empty($page->parent))
		{
			// @todo refactor
			if ($page->parent instanceof Layout)
			{
				break;
			}

			$page = $page->parent;
			$url  = $page->url . '/' . $url;
		}

		if ($url[0] != '/')
		{
			$url = '/' . $url;
		}

		return '/index.php' . $url;
	}

	/**
	 * Render a tree
	 *
	 * @param   Tree $tree The tree
	 *
	 * @return  void
	 */
	public function visitTree(Tree $tree)
	{
		$tree->setId('tree-' . spl_object_hash($tree));

		$this->preRenderChildElements($tree);

		$this->applyLayout('Tree', $tree);
	}
}
