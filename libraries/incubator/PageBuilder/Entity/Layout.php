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
 * Class Layout
 *
 * @package  Joomla\PageBuilder\Entity
 *
 * @since    __DEPLOY_VERSION__
 */
class Layout
{
	/**
	 * @var integer The ID
	 */
	public $id;

	/**
	 * @var string The layout title
	 */
	public $title;

	/**
	 * @var Layout The parent layout, if any
	 */
	public $parent;

	/**
	 * @var Template The template
	 */
	public $template;

	/**
	 * @var RepositoryInterface Subsequent layouts
	 */
	public $children;

	/**
	 * @var RepositoryInterface Pages using this layout
	 */
	public $pages;

	/**
	 * @var RepositoryInterface Content tree
	 */
	public $content;
}
