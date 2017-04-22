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
 * DataTable ContentType
 *
 * @package  Joomla/Content
 * @since    __DEPLOY_VERSION__
 *
 * @property object $data
 */
class DataTable extends AbstractCompoundType
{
	/**
	 * Tree constructor.
	 *
	 * @param string     $title  The title
	 * @param array      $params The parameters
	 */
	public function __construct($title, array $params = [])
	{
		parent::__construct($title, 'table-' . spl_object_hash($this), $params);
	}

	/**
	 * Visits the content type.
	 *
	 * @param   ContentTypeVisitorInterface $visitor The Visitor
	 *
	 * @return  mixed
	 */
	public function accept(ContentTypeVisitorInterface $visitor)
	{
		return $visitor->visitDataTable($this);
	}
}
