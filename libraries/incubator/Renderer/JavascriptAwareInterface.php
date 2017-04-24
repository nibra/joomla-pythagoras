<?php
/**
 * Part of the Joomla Framework Renderer Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Renderer;

/**
 * JavascriptAware Interface
 *
 * @package  Joomla/Renderer
 *
 * @since    __DEPLOY_VERSION__
 */
interface JavascriptAwareInterface
{
	/**
	 * Add JavaScript in a script tag to the output.
	 * The JS code is identified by the label. Re-using a label will overwrite previous definitions.
	 *
	 * @param   string $label An identifier
	 * @param   string $url   The URL associated with that identifier
	 *
	 * @return  void
	 */
	public function addRemoteJavascript($label, $url);

	/**
	 * Embed JavaScript in the output.
	 * The JS code is identified by the label. Re-using a label will overwrite previous definitions.
	 *
	 * @param   string $label An identifier
	 * @param   string $code  The code associated with that identifier
	 *
	 * @return  void
	 */
	public function embedJavascript($label, $code);

	/**
	 * Integrate the scripts into the output.
	 *
	 * @return  void
	 */
	public function writeJavascript();
}
