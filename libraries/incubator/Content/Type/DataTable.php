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
class DataTable extends AbstractContentType
{
	/**
	 * Tree constructor.
	 *
	 * @param   object[] $data   The data to be displayed in the table
	 * @param string     $title  The title
	 * @param \stdClass  $params The parameters
	 */
	public function __construct($data, $title, $params)
	{
		parent::__construct($title, 'table-' . spl_object_hash($this), $params);

		$this->data = $data;
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
