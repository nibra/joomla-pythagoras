<?php
/**
 * Part of the Joomla PageBuilder Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\PageBuilder\Entity;

use Joomla\ORM\Repository\RepositoryInterface;

/**
 * Class Page
 *
 * @package  Joomla\PageBuilder\Entity
 *
 * @since    __DEPLOY_VERSION__
 */
class Page
{
	/**
	 * @var integer The ID
	 */
	public $id;

	/**
	 * @var string The page title
	 */
	public $title;

	/**
	 * @var string The (partial) URL of the page
	 */
	public $url;

	/**
	 * @var Page The parent page, if any
	 */
	public $parent;

	/**
	 * @var RepositoryInterface Subsequent pages
	 */
	public $children;

	/**
	 * @var RepositoryInterface Content tree
	 */
	public $content;

	/**
	 * @var Layout The layout for the page
	 */
	public $layout;
}
