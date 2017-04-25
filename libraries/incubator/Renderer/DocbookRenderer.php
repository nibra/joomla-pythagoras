<?php
/**
 * Part of the Joomla Framework Renderer Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Renderer;

use Joomla\Content\ContentTypeInterface;
use Joomla\Content\ContentTypeVisitorTrait;

/**
 * Class DocbookRenderer
 *
 * @package  Joomla/Renderer
 *
 * @since    __DEPLOY_VERSION__
 */
class DocbookRenderer extends Renderer
{
	/** @var string The MIME type */
	protected $mediatype = 'application/docbook+xml';

	use ContentTypeVisitorTrait;

	/**
	 * Common handler for different ContentTypes.
	 *
	 * @param string               $method  The name of the originally called method
	 * @param ContentTypeInterface $content The content
	 *
	 * @return mixed
	 */
	public function visit($method, $content)
	{
		throw new \LogicException($method . ' is not implemented.');
	}
}
