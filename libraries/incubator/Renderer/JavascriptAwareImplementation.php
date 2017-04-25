<?php
/**
 * Part of the Joomla Framework Renderer Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Renderer;

use Psr\Http\Message\StreamInterface;

/**
 * JavascriptAware Implementation
 *
 * This implementation can only be used within classes implementing
 * Psr\Http\Message\StreamInterface.
 *
 * @package  Joomla/Renderer
 *
 * @since    __DEPLOY_VERSION__
 */
trait JavascriptAwareImplementation
{
	/** @var  string[]  Javascript url to add to output */
	private $javascriptAwareRemote = [];

	/** @var  string[]  Javascript code to add to output */
	private $javascriptAwareEmbedded = [];

	/**
	 * Add JavaScript in a script tag to the output.
	 * The JS code is identified by the label. Re-using a label will overwrite previous definitions.
	 *
	 * @param   string $label An identifier
	 * @param   string $url   The URL associated with that identifier
	 *
	 * @return  void
	 */
	public function addRemoteJavascript($label, $url)
	{
		$this->javascriptAwareRemote[$label] = $url;
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
		$this->javascriptAwareEmbedded[$label] = $code;
	}

	/**
	 * Integrate the scripts into the output.
	 *
	 * @return  void
	 */
	public function writeJavascript()
	{
		$remote = '';
		foreach ($this->javascriptAwareRemote as $url)
		{
			$remote .= "<script src=\"{$url}\"></script>";
		}

		$embedded = '';
		$embedded .= '<script>';
		$embedded .= implode("\n", $this->javascriptAwareEmbedded);
		$embedded .= '</script>';

		/** @var Renderer $this */
		$this->output = str_replace('</head>', $remote . '</head>', $this->output);
		$this->output = str_replace('</body>', $embedded . '</body>', $this->output);
	}
}
