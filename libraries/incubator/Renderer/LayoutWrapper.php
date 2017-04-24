<?php
/**
 * Part of the Joomla Framework Renderer Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Renderer;

use Joomla\Content\ContentTypeInterface;
use Joomla\Renderer\Exception\NotFoundException;

/**
 * Class LayoutWrapper
 *
 * The LayoutWrapper wraps plain layout files (as known from the old JLayout files) into an object implementing the
 * Joomla\Renderer\LayoutInterface.
 *
 * @package Joomla\Renderer
 */
class LayoutWrapper implements LayoutInterface
{
	private $debug = true;

	/**
	 * @var string
	 */
	private $layoutFile = null;

	/**
	 * @var ContentTypeInterface
	 */
	private $content = null;

	/**
	 * LayoutWrapper constructor.
	 *
	 * @param string               $contentType The content type, usually the base class name of the content element.
	 * @param ContentTypeInterface $content     The content
	 * @param string[]             $paths       Search paths for the layout file.
	 *
	 * @throws NotFoundException if the layout file could not be located within the provided paths.
	 */
	public function __construct($contentType, ContentTypeInterface $content, array $paths)
	{
		$filename = lcfirst($contentType) . '.php';

		foreach ($paths as $path)
		{
			$layoutFile = $path . '/' . $filename;

			if (file_exists($layoutFile))
			{
				$this->layoutFile = $layoutFile;
				break;
			}
		}

		if (empty($this->layoutFile))
		{
			throw new NotFoundException(sprintf('Unable to find layout file %s (search path %s)', $filename, implode(':', $paths)));
		}

		$this->content = $content;
	}

	/**
	 * Render the layout.
	 *
	 * The layout file gets its data in `$content` which is an object implementing the ContentTypeInterface.
	 * It has access to the renderer in the `$renderer` variable.
	 *
	 * @param Renderer|RendererInterface $renderer
	 *
	 * @return int
	 */
	public function render(RendererInterface $renderer)
	{
		/** @noinspection PhpUnusedLocalVariableInspection */
		$content = $this->content;

		ob_start();

		if ($this->debug)
		{
			echo "\n\n<!-- BOF {$this->layoutFile} -->\n";
		}

		include $this->layoutFile;

		if ($this->debug)
		{
			echo "\n<!-- EOF {$this->layoutFile} -->\n\n";
		}

		return $renderer->write(ob_get_clean());
	}
}
