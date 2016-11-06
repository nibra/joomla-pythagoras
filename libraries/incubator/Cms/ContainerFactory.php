<?php
/**
 * Part of the Joomla CMS Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Cms;

use Joomla\DI\Loader\IniLoader;

/**
 * ContainerFactory class
 *
 * @package  Joomla/Cms
 *
 * @since    __DEPLOY_VERSION__
 */
abstract class ContainerFactory
{
	static private $container = null;

	static public function getInstance()
	{
		if (is_null((self::$container)))
		{
			self::$container = self::initContainer();
		}

		return self::$container;
	}

	/**
	 * Create the container
	 *
	 * @return  \Joomla\DI\Container
	 */
	static private function initContainer()
	{
		$container     = new \Joomla\DI\Container;
		$rootDirectory = getcwd();

		$container->set('ConfigDirectory', $rootDirectory);

		(new IniLoader($container))->loadFromFile($rootDirectory . '/config/services.ini');

		if (!defined('JPATH_ROOT'))
		{
			define('JPATH_ROOT', $container->get('config')->get('JPATH_ROOT', $rootDirectory));
		}

		return $container;
	}
}
