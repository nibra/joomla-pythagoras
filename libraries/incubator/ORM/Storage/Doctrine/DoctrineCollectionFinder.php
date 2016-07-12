<?php
/**
 * Part of the Joomla Framework ORM Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\ORM\Storage\Doctrine;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception\SyntaxErrorException;
use Doctrine\DBAL\Query\QueryBuilder;
use Joomla\ORM\Entity\EntityBuilder;
use Joomla\ORM\Entity\EntityRegistry;
use Joomla\ORM\Exception\InvalidOperatorException;
use Joomla\ORM\Operator;
use Joomla\ORM\Storage\CollectionFinderInterface;

/**
 * Class DoctrineCollectionFinder
 *
 * @package Joomla/ORM
 *
 * @since   1.0
 */
class DoctrineCollectionFinder implements CollectionFinderInterface
{
	/** @var string[] the columns */
	private $columns = [];

	/** @var string[] the conditions */
	private $conditions = [];

	/** @var string[] the ordering */
	private $ordering = [];

	/** @var Connection the connection to work on */
	private $connection = null;

	/** @var string */
	private $tableName = null;

	/** @var string */
	private $entityClass = null;

	/** @var EntityBuilder */
	private $builder = null;

	/** @var  EntityRegistry */
	private $entityRegistry;

	/** @var array */
	private $patterns = [];

	/**
	 * DoctrineCollectionFinder constructor.
	 *
	 * @param   Connection     $connection     The database connection
	 * @param   string         $tableName      The name of the table
	 * @param   string         $entityClass    The class of the entity
	 * @param   EntityBuilder  $builder        The entity builder
	 * @param   EntityRegistry $entityRegistry The entity registry
	 */
	public function __construct(Connection $connection, $tableName, $entityClass, EntityBuilder $builder, EntityRegistry $entityRegistry)
	{
		$this->connection     = $connection;
		$this->tableName      = $tableName;
		$this->entityClass    = $entityClass;
		$this->builder        = $builder;
		$this->entityRegistry = $entityRegistry;
	}

	/**
	 * Define the columns to be retrieved.
	 *
	 * @param   array $columns The column names
	 *
	 * @return  CollectionFinderInterface  $this for chaining
	 */
	public function columns($columns)
	{
		$this->columns = $columns;

		return $this;
	}

	/**
	 * Define a condition.
	 *
	 * @param   mixed  $lValue The left value for the comparision
	 * @param   string $op     The comparision operator, one of the \Joomla\ORM\Finder\Operator constants
	 * @param   mixed  $rValue The right value for the comparision
	 *
	 * @return  CollectionFinderInterface  $this for chaining
	 */
	public function with($lValue, $op, $rValue)
	{
		switch ($op)
		{
			case Operator::CONTAINS:
				$lValue = "$lValue LIKE ?";
				$rValue = "%$rValue%";
				break;

			case Operator::STARTS_WITH:
				$lValue = "$lValue LIKE ?";
				$rValue = "$rValue%";
				break;

			case Operator::ENDS_WITH:
				$lValue = "$lValue LIKE ?";
				$rValue = "%$rValue";
				break;

			case Operator::MATCHES:
				$this->patterns[$lValue] = $rValue;

				return $this;

			case Operator::IN:
				$lValue = "$lValue IN (?" . str_repeat(',?', count($rValue) - 1) . ")";
				break;

			default:
				$lValue = "$lValue $op ?";
				break;
		}

		$this->conditions[$lValue] = $rValue;

		return $this;
	}

	/**
	 * Set the ordering.
	 *
	 * @param   string $column    The name of the ordering column
	 * @param   string $direction One of 'ASC' (ascending) or 'DESC' (descending)
	 *
	 * @return  CollectionFinderInterface  $this for chaining
	 */
	public function orderBy($column, $direction = 'ASC')
	{
		$this->ordering[$column] = $direction;

		return $this;
	}

	/**
	 * Fetch the entities
	 *
	 * @param   int $count The number of matching entities to retrieve
	 * @param   int $start The index of the first entity to retrieve
	 *
	 * @return  array
	 */
	public function getItems($count = null, $start = 0)
	{
		$builder = $this->connection->createQueryBuilder();
		$builder
			->select(empty($this->columns) ? '*' : $this->columns)
			->from($this->tableName);

		$builder = $this->applyConditions($builder);
		$builder = $this->applyOrdering($builder);

		$builder
			->setMaxResults($count)
			->setFirstResult($start);

		try
		{
			$rows = $builder
				->execute()
				->fetchAll(\PDO::FETCH_ASSOC);
		}
		catch (SyntaxErrorException $e)
		{
			throw new InvalidOperatorException($e->getMessage(), 0, $e);
		}

		foreach ($this->patterns as $column => $pattern)
		{
			$rows = array_filter(
				$rows,
				function ($row) use ($column, $pattern)
				{
					return preg_match("~{$pattern}~", $row[$column]);
				}
			);
		}

		if (empty($rows))
		{
			return [];
		}

		if (empty($this->columns))
		{
			$rows = $this->castToEntity($rows);
		}

		return array_values($rows);
	}

	/**
	 * Cast array to entity
	 *
	 * @param   array $matches The records
	 *
	 * @return  array
	 */
	private function castToEntity($matches)
	{
		$entities = $this->builder->castToEntity($matches, $this->entityClass);

		foreach ($entities as &$entity)
		{
			$this->entityRegistry->registerEntity($entity);
		}

		return $entities;
	}

	/**
	 * @param   QueryBuilder $builder The query builder
	 *
	 * @return  QueryBuilder
	 */
	private function applyConditions($builder)
	{
		$counter = 0;

		foreach ($this->conditions as $left => $value)
		{
			$builder->andWhere($left);

			foreach ((array) $value as $v)
			{
				$builder->setParameter($counter, $v);
				$counter++;
			}
		}

		return $builder;
	}

	/**
	 * @param   QueryBuilder $builder The query builder
	 *
	 * @return  QueryBuilder
	 */
	private function applyOrdering($builder)
	{
		foreach ($this->ordering as $column => $order)
		{
			$builder->orderBy($column, $order);
		}

		return $builder;
	}
}
