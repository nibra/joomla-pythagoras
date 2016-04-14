<?php
/**
 * Part of the Joomla Framework DI Package
 *
 * @copyright  Copyright (C) 2013 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
namespace Joomla\DI\Loader;

/**
 * Defines the interface for a service provider loader.
 */
interface LoaderInterface
{

	/**
	 * Loads service providers from the content.
	 *
	 * @param string $content
	 */
	public function load ($content);
}