<?php
/**
 * Part of the Joomla Framework Content Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Content\Type;

use Joomla\Content\ContentTypeVisitorInterface;

/**
 * Icon ContentType
 *
 * @package  Joomla/Content
 * @since    __DEPLOY_VERSION__
 *
 * @property object $item
 */
class Icon extends AbstractContentType
{

	/**
	 * @var string
	 */
	public $name;

	/**
	 * Icon constructor.
	 *
	 * @param $name
	 */
	public function __construct($name)
	{
		parent::__construct($name, 'icon-' . spl_object_hash($this), []);

		$this->name = $name;
	}

	/**
	 * Visits the content type.
	 *
	 * @param   ContentTypeVisitorInterface $visitor The Visitor
	 *
	 * @return  void
	 */
	public function accept(ContentTypeVisitorInterface $visitor)
	{
		$visitor->visitIcon($this);
	}
}
