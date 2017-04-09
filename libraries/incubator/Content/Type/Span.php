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
 * Span ContentType
 *
 * @package  Joomla/Content
 * @since    __DEPLOY_VERSION__
 *
 * @property string $text
 */
class Span extends AbstractContentType
{
	/**
	 * Paragraph constructor.
	 *
	 * @param   string $text     The copy of the paragraph
	 * @param   array  $params   The parameters. Supported values:
	 *                           'class': The CSS class
	 */
	public function __construct($text, $params = [])
	{
		parent::__construct('Span', 'span-' . spl_object_hash($this), $params);

		$this->text = $text;
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
		return $visitor->visitSpan($this);
	}
}
