<?php
/**
 * Part of the Joomla Framework Renderer Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Renderer;

/**
 * CssAware Interface
 *
 * @package  Joomla/Renderer
 *
 * @since    __DEPLOY_VERSION__
 */
interface CssAwareInterface
{
	/**
	 * Add stylesheet to the output.
	 * The stylesheet is identified by the label. Re-using a label will overwrite previous definitions.
	 *
	 * @param   string $label An identifier
	 * @param   string $url   The URL associated with that identifier
	 *
	 * @return  void
	 */
	public function addRemoteCss($label, $url);

	/**
	 * Embed CSS in the output.
	 * The CSS code is namespaced with the ID of the element to prevent collisions.
	 *
	 * @param   string $namespace ID of the element
	 * @param   string $css       The CSS code
	 *
	 * @return  void
	 */
	public function embedCss($namespace, $css);

	/**
	 * Integrate the scripts into the output.
	 *
	 * @return  void
	 */
	public function writeCss();
}
