<?php
/**
 * Part of the Joomla Framework Renderer Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Renderer;

/**
 * Layout Interface
 *
 * @package  Joomla/Renderer
 *
 * @since    __DEPLOY_VERSION__
 */
interface LayoutInterface
{
	/**
	 * Render the layout.
	 *
	 * @param Renderer $renderer
	 *
	 * @return string
	 */
	public function render(Renderer $renderer);
}
