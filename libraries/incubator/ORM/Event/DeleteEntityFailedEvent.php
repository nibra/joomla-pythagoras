<?php
/**
 * Part of the Joomla Framework ORM Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\ORM\Event;

use Joomla\Event\Event;
use Joomla\ORM\Entity\EntityBuilder;

/**
 * Class DeleteEntityFailedEvent
 *
 * @package Joomla\ORM
 *
 * @since   __DEPLOY_VERSION__
 */
class DeleteEntityFailedEvent extends Event
{
	/**
	 * DeleteEntityFailedEvent constructor.
	 *
	 * @param   object        $entity  The entity
	 */
	public function __construct($entity, \Exception $exception)
	{
		parent::__construct(
			'onDeleteEntityFailed',
			[
				'entity' => $entity,
			    'exception' => $exception
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

	/**
	 * @return   \Exception
	 */
	public function getException()
	{
		return $this->getArgument('exception');
	}
}
