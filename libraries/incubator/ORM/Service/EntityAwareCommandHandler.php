<?php
/**
 * Part of the Joomla Framework ORM Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\ORM\Service;

use Interop\Container\ContainerInterface;
use Joomla\Cli\Exception\InvalidFilterException;
use Joomla\Cli\Exception\NoRecordsException;
use Joomla\Event\DispatcherInterface;
use Joomla\ORM\Repository\RepositoryInterface;
use Joomla\ORM\Storage\CollectionFinderInterface;
use Joomla\Service\CommandBus;
use Joomla\Service\CommandHandler;
use Joomla\String\Inflector;

/**
 * The Delete command deletes entities.
 *
 * @package     Joomla\ORM
 * @since       __DEPLOY_VERSION__
 */
abstract class EntityAwareCommandHandler extends CommandHandler
{
	/** @var ContainerInterface */
	protected $container;

	/** @var  RepositoryFactory */
	protected $repositoryFactory;

	public function __construct(CommandBus $commandBus, DispatcherInterface $dispatcher)
	{
		$this->repositoryFactory = $commandBus->handle(new RepositoryFactoryQuery);

		parent::__construct($commandBus, $dispatcher);
	}

	public function handle(EntityAwareCommand $command)
	{
		$entityName = $this->normaliseEntityName($command->entityName);
		$repository = $this->repositoryFactory->forEntity($entityName);
		$finder     = $this->applyFilter($command->filter, $repository->findAll());

		$this->doIt($repository, $finder);
	}

	/**
	 * Normalises the entity name.
	 *
	 * @param   string $entity The entity name (singular or plural)
	 *
	 * @return  string The singular entity name
	 */
	protected function normaliseEntityName($entity)
	{
		$inflector = Inflector::getInstance();

		if ($inflector->isPlural($entity))
		{
			$entity = $inflector->toSingular($entity);
		}

		return ucfirst($entity);
	}

	/**
	 * Applies the filter conditions
	 *
	 * @param   string[]                  $conditions The filter options
	 * @param   CollectionFinderInterface $finder     The finder
	 *
	 * @return  CollectionFinderInterface
	 */
	protected function applyFilter($conditions, $finder)
	{
		foreach ($conditions as $filter)
		{
			if (!preg_match('~^(\w+)(\s*\W+\s*|\s+\w+\s+)(.+)$~', trim($filter), $match))
			{
				throw new InvalidFilterException("Cannot interpret filter $filter");
			}

			$finder = $finder->with($match[1], trim($match[2]), $match[3]);
		}

		return $finder;
	}

	/**
	 * Retrieves the selected records.
	 *
	 * @param   CollectionFinderInterface $finder The finder
	 *
	 * @return  object[]
	 */
	protected function getRecords($finder)
	{
		$records = $finder->getItems();

		if (empty($records))
		{
			throw new NoRecordsException("No matching records found");
		}

		return $records;
	}

	/**
	 * @param RepositoryInterface $repository
	 * @param CollectionFinderInterface $finder
	 */
	abstract protected function doIt($repository, $finder);
}
