<?php
/**
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */
namespace Joomla\Service;
use Dotenv\Dotenv;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Registry\Registry;
use Joomla\DI\Container;

/**
 * Load the Configuration Data.
 *
 * The configuration is read from a file in the directory passed to the
 * constructor (defaults to `.env`).
 *
 * @since 1.0
 */
class ConfigServiceProvider implements ServiceProviderInterface
{

	/** @var string Path to `.env` file */
	private $path;

	/** @var string Name of the `.env` file */
	private $file;

	/**
	 * ConfigServiceProvider constructor.
	 *
	 * @param string $path
	 *        	Path to `.env` file
	 * @param string $file
	 *        	Name of the `.env` file
	 */
	public function __construct ($path, $file = '.env')
	{
		$this->path = $path;
		$this->file = $file;
	}

	public function register (Container $container, $alias = 'config')
	{
		$dotenv = new Dotenv($this->path, $this->file);
		$dotenv->overload();

		$container->set($alias, new Registry($_ENV), true);
	}
}
