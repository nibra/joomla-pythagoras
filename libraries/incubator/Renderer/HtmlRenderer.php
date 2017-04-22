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
class HtmlRenderer extends Renderer
{
	/** @var string The MIME type */
	protected $mediatype = 'text/html';

	/** @var string  Template directory */
	protected $template;

	/** @var string  Layout directory */
	protected $layoutDirectory = 'bootstrap-3';

	/** @var  ScriptStrategyInterface */
	private $clientScript;

	/** @var  string[]  Javascript url to add to output */
	private $scriptUrl = [];

	/** @var  string[]  Javascript code to add to output */
	private $javascript = [];

	/** @var  string[]  CSS code to add to output */
	private $css = [];

	/** @var  string[]  CSS url to add to output */
	private $styleUrl = [];

	/** @var  LayoutFactory */
	private $layoutFactory;

	use DumpTrait;

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
	 * Add JavaScript in a script tag to the output.
	 * The JS code is identified by the label. Re-using a label will overwrite previous definitions.
	 *
	 * @param   string $label An identifier
	 * @param   string $url   The code associated with that identifier
	 *
	 * @return  void
	 */
	public function addJavascript($label, $url)
	{
		$this->scriptUrl[$label] = $url;
	}

	/**
	 * Embed JavaScript in the output.
	 * The JS code is identified by the label. Re-using a label will overwrite previous definitions.
	 *
	 * @param   string $label An identifier
	 * @param   string $code  The code associated with that identifier
	 *
	 * @return  void
	 */
	public function embedJavascript($label, $code)
	{
		$this->javascript[$label] = $code;
	}

	/**
	 * Add stylesheet to the output.
	 * The stylesheet is identified by the label. Re-using a label will overwrite previous definitions.
	 *
	 * @param string $label
	 * @param string $url
	 *
	 * @return void
	 */
	public function addCss($label, $url)
	{
		$this->styleUrl[$label] = $url;
	}

	/**
	 * Embed CSS in the output.
	 * The CSS code is namespaced with the ID of the element to prevent collisions.
	 *
	 * @param string $namespace
	 * @param string $css
	 *
	 * @return void
	 */
	public function embedCss($namespace, $css)
	{
		$this->css[] = preg_replace_callback(
			'~([^{\s]*\s?\{[^{]*?\})~sm',
			function ($match) use ($namespace)
			{
				return "#{$namespace} {$match[0]}";
			},
			$css
		);
	}

	/**
	 * @return  void
	 */
	public function writeEmbeddedJavascript()
	{
		$js = '';
		$js .= '<script>';
		$js .= implode("\n", $this->javascript);
		$js .= '</script>';

		$this->injectBefore('</body>', $js);
	}

	/**
	 * @return  void
	 */
	public function writeRemoteJavascript()
	{
		$elements = '';
		foreach ($this->scriptUrl as $url)
		{
			$elements .= "<script src=\"{$url}\"></script>";
		}

		$this->injectBefore('</head>', $elements);
	}

	/**
	 * @return  void
	 */
	public function writeEmbeddedCss()
	{
		$css = '';
		$css .= '<style>';
		$css .= implode("\n", $this->css);
		$css .= '</style>';

		$this->injectBefore('</head>', $css);
	}

	/**
	 * @return  void
	 */
	public function writeRemoteCss()
	{
		$elements = '';
		foreach ($this->styleUrl as $url)
		{
			$elements .= "<link rel=\"stylesheet\" href=\"{$url}\">";
		}

		$this->injectBefore('</head>', $elements);
	}

	public function close()
	{
		$this->writeRemoteJavascript();
		$this->writeEmbeddedJavascript();
		$this->writeRemoteCss();
		$this->writeEmbeddedCss();

		#parent::close();
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
		return $this->write($this->layoutFactory->createLayout($contentType, $content)->render($this));
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
		return $this->applyLayout('Attribution', $attribution);
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
		return $this->applyLayout('Paragraph', $paragraph);
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
		$id = " id=\"{$compound->getId()}\"";

		$class = $compound->getParameter('class', '');

		if (!empty($class))
		{
			$class = " class=\"$class\"";
		}

		$len = 0;
		$len += $this->write("<{$compound->getType()}{$id}{$class}>\n");

		foreach ($compound->elements as $item)
		{
			$len += $item->accept($this);
		}

		$len += $this->write("</{$compound->getType()}>\n");

		return $len;
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
		return $this->applyLayout('Image', $image);
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
		$slider->setId('slider-' . spl_object_hash($slider));

		$this->preRenderChildElements($slider);

		return $this->applyLayout('Slider', $slider);
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
	 * Render an accordion
	 *
	 * @param   Accordion $accordion The accordion
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitAccordion(Accordion $accordion)
	{
		$accordion->setId('accordion-' . spl_object_hash($accordion));

		$this->preRenderChildElements($accordion);

		return $this->applyLayout('Accordion', $accordion);
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
		$tree->setId('tree-' . spl_object_hash($tree));

		$this->preRenderChildElements($tree);

		return $this->applyLayout('Tree', $tree);
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
		$tabs->setId('tabs-' . spl_object_hash($tabs));

		$this->preRenderChildElements($tabs);

		return $this->applyLayout('Tabs', $tabs);
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
		return $this->write('<pre>' . $this->dumpEntity($dump->item) . '</pre>');
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
		$this->preRenderChildElements($rows);

		return $this->applyLayout('Rows', $rows);
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
		$this->preRenderChildElements($columns);

		return $this->applyLayout('Columns', $columns);
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
		return $this->applyLayout('Article', $article);
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
		$teaser->url = $this->getFullUrl($teaser->article);

		return $this->applyLayout('Teaser', $teaser);
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
	 * Render a defaultMenu
	 *
	 * @param   DefaultMenu $defaultMenu The defaultMenu
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitDefaultMenu(DefaultMenu $defaultMenu)
	{
		if ($defaultMenu->item instanceof Page)
		{
			$defaultMenu->item = $this->convertPageTreeToMenu($defaultMenu->item);
		}

		return $this->applyLayout('DefaultMenu', $defaultMenu);
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
	 * Render a data table
	 *
	 * @param   DataTable $dataTable The data table
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitDataTable(DataTable $dataTable)
	{
		/** @var EntityBuilder $entityBuilder */
		$entityBuilder = $this->container->get('Repository')->getEntityBuilder();

		$params           = $dataTable->getParameters();
		$params['entity'] = $entityBuilder->getMeta(get_class($dataTable->getChildren()[0]->item));
		$dataTable->setParameters($params);

		return $this->applyLayout('DataTable', $dataTable);
	}

	/**
	 * @return  array
	 */
	protected function collectMetadata()
	{
		$metaData                                  = parent::collectMetadata();
		$metaData['wrapper_data']['client_script'] = empty($this->clientScript) ? null : get_class($this->clientScript);

		return $metaData;
	}

	/**
	 * Render a horizontal line.
	 *
	 * @param   HorizontalLine $headline The horizontal line
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitHorizontalLine(HorizontalLine $headline)
	{
		return $this->write("<hr>\n");
	}

	/**
	 * Render a span element
	 *
	 * @param   Span $span The text
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitSpan(Span $span)
	{
		return $this->applyLayout('Span', $span);
	}

	/**
	 * Render an OnePager
	 *
	 * @param   OnePager $page The page
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitOnePager(OnePager $page)
	{
		$this->preRenderChildElements($page);

		return $this->applyLayout('Onepager', $page);
	}

	/**
	 * Render an OnePager section
	 *
	 * @param   OnePagerSection $section The page
	 *
	 * @return  integer Number of bytes written to the output
	 */
	public function visitOnePagerSection(OnePagerSection $section)
	{
		$this->preRenderChildElements($section);

		return $this->applyLayout('OnepagerSection', $section);
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
		return $this->applyLayout('Icon', $icon);
	}

	/**
	 * Render a link
	 *
	 * @param Link $link
	 *
	 * @return int Number of bytes written to the output
	 */
	public function visitLink(Link $link)
	{
		return $this->applyLayout('Link', $link);
	}

	/**
	 * @param $where
	 * @param $string
	 */
	private function injectBefore($where, $string)
	{
		$this->output = str_replace($where, $string . $where, $this->output);
	}
}
