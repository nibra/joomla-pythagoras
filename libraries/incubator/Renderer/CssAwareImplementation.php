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
 * CssAware Implementation
 *
 * This implementation can only be used within classes implementing
 * Psr\Http\Message\StreamInterface.
 *
 * @package  Joomla/Renderer
 *
 * @since    __DEPLOY_VERSION__
 */
trait CssAwareImplementation
{
	/** @var  string[]  Css url to add to output */
	private $cssAwareRemote = [];

	/** @var  string[]  Css code to add to output */
	private $cssAwareEmbedded = [];

	/**
	 * Add stylesheet to the output.
	 * The stylesheet is identified by the label. Re-using a label will overwrite previous definitions.
	 *
	 * @param   string $label An identifier
	 * @param   string $url   The URL associated with that identifier
	 *
	 * @return  void
	 */
	public function addRemoteCss($label, $url)
	{
		$this->cssAwareRemote[$label] = $url;
	}

	/**
	 * Embed CSS in the output.
	 * The CSS code is namespaced with the ID of the element to prevent collisions.
	 *
	 * @param   string $namespace ID of the element
	 * @param   string $css       The CSS code
	 *
	 * @return  void
	 */
	public function embedCss($namespace, $css)
	{
		$this->cssAwareEmbedded[] = preg_replace_callback(
			'~([^{\s]*\s?\{[^{]*?\})~sm',
			function ($match) use ($namespace)
			{
				return "#{$namespace} {$match[0]}";
			},
			$css
		);
	}

	/**
	 * Integrate the scripts into the output.
	 *
	 * @return  void
	 */
	public function writeCss()
	{
		$remote = '';
		foreach ($this->cssAwareRemote as $url)
		{
			$remote .= "<link rel=\"stylesheet\" href=\"{$url}\">";
		}

		$embedded = '';
		$embedded .= '<style>';
		$embedded .= implode("\n", $this->cssAwareEmbedded);
		$embedded .= '</style>';

		/** @var Renderer $this */
		$this->output = str_replace('</head>', $remote . '</head>', $this->output);
		$this->output = str_replace('</head>', $embedded . '</head>', $this->output);
	}
}
