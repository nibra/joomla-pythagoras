<?php
/**
 * Part of the Joomla Framework ORM Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\ORM\Event;

use Joomla\Event\Event;

/**
 * Class EntityDeletedEvent
 *
 * @package Joomla\ORM
 *
 * @since   __DEPLOY_VERSION__
 */
class EntityDeletedEvent extends Event
{
	/**
	 * EntityDeletedEvent constructor.
	 *
	 * @param   object $entity The entity
	 */
	public function __construct($entity)
	{
		parent::__construct(
			'onEntityDeleted',
			[
				'entity' => $entity
			]
		);
	}

	/**
	 * @return   object
	 */
	public function getEntity()
	{
		return $this->getArgument('entity');
	}
}
