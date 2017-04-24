<?php
/**
 * Part of the Joomla Framework Renderer Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Renderer;

use Joomla\String\Inflector;

/**
 * Class LayoutFactory
 *
 * @package Joomla\Renderer
 */
class LayoutFactory
{
	/**
	 * @var string[]
	 */
	private $paths;

	/**
	 * @var string[]
	 */
	private $namespaces;

	/**
	 * LayoutFactory constructor.
	 *
	 * @param string[] $paths
	 */
	public function __construct($paths)
	{
		$this->paths      = $paths;
		$this->paths[]    = 'layouts/plain';
		$this->namespaces = $this->getNamespaces($this->paths);
	}

	private function getNamespaces($paths)
	{
		$inflector = Inflector::getInstance();

		$namespaces = [];

		foreach ($paths as $path)
		{
			$separated = str_replace('/', ' ', $path);
			$separated = preg_replace('~[^\w ]+~', '', $separated);
			$parts     = explode(' ', $separated);
			array_walk(
				$parts,
				function (&$part) use ($inflector)
				{
					if ($inflector->isPlural($part))
					{
						$part = $inflector->toSingular($part);
					}
					$part = ucfirst($part);
				}
			);

			if ($parts[0] == 'Layout')
			{
				array_unshift($parts, 'Joomla');
			}
			elseif ($parts[0] == 'Template')
			{
				// @todo Get real template namespace, e.g. from database
				array_shift($parts);
			}

			$namespaces[] = implode('\\', $parts);
		}

		return $namespaces;
	}

	/**
	 * @param $contentType
	 *
	 * @param $content
	 *
	 * @return LayoutInterface
	 */
	public function createLayout($contentType, $content)
	{
		$layout = null;
		foreach ($this->namespaces as $namespace)
		{
			$className = $namespace . '\\' . $contentType;

			if (class_exists($className))
			{
				$layout = new $className($content);
				break;
			}
		}

		if (is_null($layout))
		{
			$layout = new LayoutWrapper($contentType, $content, $this->paths);
		}

		return $layout;
	}
}
