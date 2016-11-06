<?php
/**
 * Part of the Joomla Framework ORM Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\ORM\Service;

use Joomla\ORM\Event\DeleteEntityEvent;
use Joomla\ORM\Event\DeleteEntityFailedEvent;
use Joomla\ORM\Event\EntityDeletedEvent;
use Joomla\ORM\Repository\RepositoryInterface;
use Joomla\ORM\Storage\CollectionFinderInterface;

/**
 * The Delete command deletes entities.
 *
 * @package     Joomla\ORM
 * @since       __DEPLOY_VERSION__
 */
class DeleteCommandHandler extends EntityAwareCommandHandler
{
	/**
	 * @param RepositoryInterface       $repository
	 * @param CollectionFinderInterface $finder
	 */
	protected function doIt($repository, $finder)
	{
		$dispatcher = $this->getDispatcher();
		$record     = null;

		try
		{
			$events = [];

			foreach ($this->getRecords($finder) as $record)
			{
				$dispatcher->dispatch(new DeleteEntityEvent($record));
				$repository->remove($record);
				$events[] = new EntityDeletedEvent($record);
			}

			$repository->commit();

			foreach ($events as $event)
			{
				$dispatcher->dispatch($event);
			}
		}
		catch (\Exception $exception)
		{
			$dispatcher->dispatch(new DeleteEntityFailedEvent($record, $exception));
		}
	}
}
