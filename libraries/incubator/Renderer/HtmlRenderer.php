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
use Joomla\Content\ContentTypeVisitorTrait;
use Joomla\Content\Type\Article;
use Joomla\Content\Type\Attribution;
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

	/** @var  LayoutFactory */
	private $layoutFactory;

	use DumpTrait;
	use JavascriptAwareImplementation;
	use CssAwareImplementation;
	use ContentTypeVisitorTrait;

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
	 * Common handler for different ContentTypes.
	 *
	 * @param string               $method  The name of the originally called method
	 * @param ContentTypeInterface $content The content
	 *
	 * @return void
	 */
	public function visit($method, $content)
	{
		$contentType = str_replace('visit', '', $method);
		$this->preRenderChildElements($content);
		$this->applyLayout($contentType, $content);
	}

	/**
	 * Pre-render child elements.
	 *
	 * This is usually only needed in plain text layouts.
	 *
	 * @param   ContentTypeInterface $content The content element
	 *
	 * @return  void
	 * @todo    Move this method to LayoutWrapper
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
	 * Apply a layout
	 *
	 * @param   string                      $contentType The filename of the layout file
	 * @param   object|ContentTypeInterface $content     The content
	 *
	 * @return  void
	 */
	private function applyLayout($contentType, $content)
	{
		$this->layoutFactory->createLayout($contentType, $content)->render($this);
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
}
