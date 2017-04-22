<?php
/**
 * Part of the Joomla PageBuilder Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\PageBuilder;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Logging\DebugStack;
use Interop\Container\ContainerInterface;
use Joomla\Content\CompoundTypeInterface;
use Joomla\Content\ContentTypeInterface;
use Joomla\Content\Type\AbstractContentType;
use Joomla\Content\Type\Accordion;
use Joomla\Content\Type\Compound;
use Joomla\Content\Type\Image;
use Joomla\Event\DispatcherInterface;
use Joomla\Media\Entity\Image as ImageEntity;
use Joomla\ORM\Operator;
use Joomla\ORM\Repository\RepositoryInterface;
use Joomla\ORM\Service\RepositoryFactory;
use Joomla\PageBuilder\ContentType\TemplateSelector;
use Joomla\PageBuilder\Entity\Content;
use Joomla\PageBuilder\Entity\Layout;
use Joomla\PageBuilder\Entity\Page;
use Joomla\PageBuilder\Entity\Template;
use Joomla\Renderer\HtmlRenderer;
use Joomla\Service\CommandBus;
use Joomla\Service\CommandHandler;
use Joomla\String\Inflector;
use Joomla\String\Normalise;
use Joomla\Tests\Unit\DumpTrait;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Class DisplayPageCommandHandler
 *
 * @package Joomla\PageBuilder
 */
class DisplayPageCommandHandler extends CommandHandler
{
	use DumpTrait;

	/** @var  ContainerInterface */
	private $container;

	/** @var  StreamInterface|HtmlRenderer */
	private $output;

	/** @var  string[] */
	private $vars;

	/** @var  ServerRequestInterface The request object */
	private $request;

	/** @var  Inflector */
	private $inflector;

	/**
	 * Constructor.
	 *
	 * @param   CommandBus          $commandBus A command bus
	 * @param   DispatcherInterface $dispatcher A dispatcher
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function __construct(CommandBus $commandBus, DispatcherInterface $dispatcher)
	{
		parent::__construct($commandBus, $dispatcher);

		$this->inflector = Inflector::getInstance();
	}

	/**
	 * @param DisplayPageCommand $command
	 */
	public function handle(DisplayPageCommand $command)
	{
		$id              = $command->getId();
		$this->vars      = $command->getVars();
		$this->request   = $command->getRequest();
		$this->output    = $command->getStream();
		$this->container = $command->getContainer();

		$this->registerContentTypes();

		/** @var RepositoryFactory $repositoryFactory */
		$repositoryFactory = $this->container->get('Repository');
		$repository        = $repositoryFactory->forEntity(Page::class);

		$page           = $repository->getById($id);
		$contentItems[] = $page->content->getAll();

		for ($m = $page->layout; $m != null; $m = $m->parent)
		{
			array_unshift($contentItems, $m->content->getAll());
		}

		$contentItems = $this->flatten($contentItems);

		if (!isset($page->parent))
		{
			$page->parent = $page->layout;
		}

		$contentTree = $this->buildTree($contentItems);

		$data = get_object_vars($page);

		if ($data['title'][0] == ':')
		{
			// @todo Retrieve the title
		}

		$templatePath = $page->layout->template->path;
		$this->output->setTemplate($templatePath);
		$template = $this->loadTemplate(JPATH_ROOT . '/' . $templatePath . '/index.php', $data);
		$parts    = preg_split('~</body>~', $template, 2);
		$parts[1] = '</body>' . $parts[1];

		$this->output->write($parts[0]);

		foreach ($contentTree as $root)
		{
			$root->accept($this->output);
		}

		$queryParams = $this->request->getQueryParams();

		if (isset($queryParams['debug']))
		{
			$this->dumpSql();
		}

		$this->output->writeJavascript();
		$this->output->writeCss();
		$this->output->write($parts[1]);
	}

	private function registerContentTypes()
	{
		$container = $this->container;
		$output    = $this->output;

		$this->output->registerContentType('TemplateSelector', function (TemplateSelector $selector) use ($container, $output)
		{
			/** @var RepositoryInterface $repo */
			$repo      = $container->get('Repository')->forEntity(Template::class);
			$templates = $repo->getAll();

			// @todo Grouping
			$groups    = [
				'Available Templates' => $templates,
			];
			$accordion = new Accordion('Accordion Title', null, []);

			foreach ($groups as $title => $group)
			{
				$compound = new Compound('div', $title, null, []);

				foreach ($group as $item)
				{
					$imageData      = new ImageEntity();
					$imageData->url = '/' . $item->path . '/preview.png';
					$image          = new Image($imageData, $item->path);
					$compound->addChild($image);
				}
				$accordion->addChild($compound);
			}

			$accordion->accept($output);
		});
	}

	private function flatten($content)
	{
		$result = [];

		foreach ($content as $item)
		{
			if (empty($item))
			{
				continue;
			}

			if (!is_array($item))
			{
				$result[$item->name] = $item;
				continue;
			}

			foreach ($this->flatten($item) as $name => $subItem)
			{
				$result[$name] = $subItem;
			}
		}

		return $result;
	}

	/**
	 * @param Layout[]|Page[] $contentItems
	 *
	 * @return ContentTypeInterface[]
	 */
	private function buildTree($contentItems)
	{
		$result = [];
		$tree   = $this->objectListToTree($contentItems);

		foreach ($tree as $root)
		{
			foreach ($this->toContentType($root) as $item)
			{
				$result[] = $item;
			}
		}

		return $result;
	}

	private function loadTemplate($path, $data = [])
	{
		extract($data);

		ob_start();
		include $path;

		return ob_get_clean();
	}

	/**
	 * @return  void
	 */
	protected function dumpSql()
	{
		$connection = $this->container->get('Repository')->getConnection();

		if (!$connection instanceof Connection)
		{
			return;
		}

		$logger = $connection->getConfiguration()->getSQLLogger();

		if (!$logger instanceof DebugStack)
		{
			return;
		}

		$queries = $logger->queries;

		$table = '<table class="debug"><tr><th>#</th><th>SQL</th><th>Time</th></tr>';

		foreach ($queries as $index => $query)
		{
			$sql    = $query['sql'];
			$params = $query['params'];

			ksort($params);

			$sql  = preg_replace_callback(
				'~\?~',
				function () use (&$params)
				{
					return array_shift($params);
				},
				$sql
			);
			$sql  = preg_replace('~(WHERE|LIMIT|INNER\s+JOIN|LEFT\s+JOIN)~', "\n  \\1", $sql);
			$sql  = preg_replace('~(AND|OR)~', "\n    \\1", $sql);
			$time = sprintf('%.3f ms', 1000 * $query['executionMS']);

			$table .= "<tr><td>$index</td><td><pre>$sql</pre></td><td>$time</td></tr>";
		}

		$table .= '</table>';

		$this->output->write($table);
	}

	private function objectListToTree(array $items, $id = 'id', $parentId = 'parentId', $children = 'children', $ordering = 'ordering')
	{
		$result = [];

		foreach ($items as $item)
		{
			$item->{$children}          = [];
			$result[(int) $item->{$id}] = $item;
		}

		foreach ($result as $id => $item)
		{
			$pid = (int) $item->{$parentId};

			if (empty($pid))
			{
				continue;
			}

			if (!isset($result[$pid]))
			{
				throw new \RuntimeException("Incomplete list - missing id $pid");
			}

			$result[$pid]->{$children}[$item->{$ordering}] = $item;
		}

		foreach ($result as $id => $item)
		{
			ksort($item->{$children});

			if (!empty($item->{$parentId}))
			{
				unset($result[$id]);
			}
		}

		return $result;
	}

	/**
	 * Build a ContentType object
	 *
	 * This method takes the root (a node) of the page's content tree (records from the contents table))
	 * and transforms it into a corresponding tree of content elements for rendering.
	 *
	 * The database fields have the following meaning:
	 *
	 * `name`         - a page-wide unique name (id) for the element. SHOULD be used as id attribute.
	 * `content_type` - fully qualified classname of the content element.
	 * `content_args` - constructor parameters for the content element.
	 * `component`    - name of the entity containing data for the content element.
	 * `selection`    - selection criteria for the data entity.
	 * `params`       - additional parameters for use by the layout file.
	 *
	 * @param Content $root
	 *
	 * @return ContentTypeInterface[]
	 */
	private function toContentType($root)
	{
		#echo "<pre>" . __METHOD__ . ' ' . $this->dumpEntity($root) . "\n";
		#echo "Data selection: " . $root->component . ' ' . print_r($root->selection, true) . "\n";
		$contentType = $root->contentType;

		$root->params = empty($root->params) ? [] : get_object_vars($root->params);

		$data = $this->findData($this->resolveComponent($root->component), $root->selection);

		$reflector             = new \ReflectionClass($contentType);
		$constructor           = $reflector->getConstructor();
		$constructorParameters = null;
		if (!empty($constructor))
		{
			$constructorParameters = $constructor->getParameters();
		}

		$all = [];

		foreach (array_values($data) as $index => $item)
		{
			#echo "<pre>";
			#echo "Data item: " . $this->dumpEntity($item) . "\n";

			/** @var AbstractContentType $content */
			$providedArguments = empty($root->contentArgs) ? [] : get_object_vars($root->contentArgs);
			$actualArguments   = [];

			foreach ($constructorParameters as $parameter)
			{
				$name         = $parameter->getName();
				$defaultValue = $parameter->isDefaultValueAvailable() ? $parameter->getDefaultValue() : null;

				if ($name == 'item')
				{
					$defaultValue = $item;
				}

				$actualArguments[$name] = isset($providedArguments[$name]) ? $providedArguments[$name] : $defaultValue;
			}

			$content = $reflector->newInstanceArgs($actualArguments);

			$content->setId($index == 0 ? $root->name : $root->name . '-' . $index);
			$content->setParameters($root->params);

			if (!empty($root->customCss))
			{
				$this->output->addCss($content->getId(), $root->customCss);
			}

			#echo "Content: " . get_class($content) . "\n";
			if ($content instanceof CompoundTypeInterface)
			{
				/** @noinspection PhpUndefinedFieldInspection */
				foreach ($root->children as $node)
				{
					#echo "<pre>";
					#echo "Child: " . $node->name . " (" . $node->contentType . ")\n";
					foreach ($this->toContentType($node) as $child)
					{
						#echo "Result: " . get_class($child) . "\n";
						$content->addChild($child);
					}
					#echo "</pre>";
				}
			}

			$all[] = $content;
			#echo "</pre>";
		}

		#echo "</pre>";

		return $all;
	}

	private function findData($component, $selection)
	{
		if (empty($component))
		{
			return [null];
		}

		/** @var RepositoryInterface $repo */
		$repo   = $this->container->get('Repository')->forEntity($component);
		$finder = $repo->findAll();

		foreach ((array) $selection as $key => $value)
		{
			if (!empty($value) && $value[0] == ':')
			{
				$value = $this->vars[substr($value, 1)];
			}
			$finder = $finder->with($key, Operator::EQUAL, $value);
		}

		return $finder->getItems();
	}

	/**
	 * Resolve the component in case it is a dynamic entity name
	 *
	 * @param   string  $component  The component
	 *
	 * @return  string
	 */
	private function resolveComponent($component)
	{
		if (!empty($component) && $component[0] == ':')
		{
			$component = $this->vars[substr($component, 1)];

			if (!$this->inflector->isPlural($component))
			{
				throw new \RuntimeException("Entity name must be provided in its plural form; try '" . $this->inflector->toPlural($component) . "'");
			}

			$component = Normalise::toCamelCase($this->inflector->toSingular($component));
		}

		return $component;
	}
}
