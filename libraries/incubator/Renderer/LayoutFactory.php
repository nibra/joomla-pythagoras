<?php
/**
 * Part of the Joomla Framework Renderer Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Renderer;

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
		$namespaces = [];

		foreach ($paths as $path)
		{
			$word         = str_replace('/', ' ', $path);
			$word         = ucwords($word);
			$word         = str_replace(' ', '\\', $word);
			$namespaces[] = $word;
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
		foreach ($this->namespaces as $namespace)
		{
			$className = $namespace . $contentType;

			if (class_exists($className))
			{
				return new $className($content);
			}
		}

		return new LayoutWrapper($contentType, $content, $this->paths);
	}
}
