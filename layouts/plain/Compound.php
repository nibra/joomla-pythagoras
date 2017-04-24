<?php
/**
 * Part of the Joomla Framework Content Package
 *
 * @copyright  Copyright (C) 2015 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Layout\Plain;

use Joomla\Renderer\LayoutInterface;
use Joomla\Renderer\RendererInterface;

class Compound implements LayoutInterface
{
	/** @var  \Joomla\Content\Type\Compound */
	private $content;

	public function __construct(\Joomla\Content\Type\Compound $content)
	{
		$this->content = $content;
	}

	/**
	 * Render the layout.
	 *
	 * @param RendererInterface $renderer
	 *
	 * @return int
	 */
	public function render(RendererInterface $renderer)
	{
		$tag   = $this->content->getType();
		$id    = $this->content->getId();
		$id    = empty($id) ? "" : " id=\"{$id}\"";
		$class = $this->content->getParameter('class');
		$class = empty($class) ? "" : " class=\"{$class}\"";

		$len = $renderer->write("<{$tag}{$id}{$class}>");

		foreach ($this->content->getChildren() as $child)
		{
			$len .= $child->accept($renderer);
		}

		$len .= $renderer->write("</{$tag}>");

		return $len;
	}
}
