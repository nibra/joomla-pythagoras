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
 * Paragraph ContentType
 *
 * @package  Joomla/Content
 * @since    __DEPLOY_VERSION__
 *
 * @property string  $text
 * @property integer $variant One of the class constants
 */
class Paragraph extends AbstractContentType
{
	const PLAIN = 0;
	const EMPHASISED = 1;

	/**
	 * Paragraph constructor.
	 *
	 * @param   string  $text    The copy of the paragraph
	 * @param   integer $variant Emphasis variant, see class constants
	 * @param   array   $params  The parameters. Supported values:
	 *                           'class': The CSS class
	 */
	public function __construct($text, $variant = self::PLAIN, $params = [])
	{
		parent::__construct('Paragraph', 'paragraph-' . spl_object_hash($this), $params);

		$this->text    = $text;
		$this->variant = $variant;
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
		$visitor->visitParagraph($this);
	}
}
