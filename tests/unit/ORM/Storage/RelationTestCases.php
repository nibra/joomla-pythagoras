<?php
namespace Joomla\Tests\Unit\ORM\Storage;

use Joomla\ORM\Definition\Locator\Locator;
use Joomla\ORM\Definition\Locator\Strategy\RecursiveDirectoryStrategy;
use Joomla\ORM\Entity\EntityBuilder;
use Joomla\ORM\Entity\EntityRegistry;
use Joomla\ORM\Entity\EntityStates;
use Joomla\ORM\IdAccessorRegistry;
use Joomla\ORM\Repository\RepositoryInterface;
use Joomla\ORM\Service\RepositoryFactory;
use Joomla\ORM\UnitOfWork\ChangeTracker;
use Joomla\ORM\UnitOfWork\TransactionInterface;
use Joomla\ORM\UnitOfWork\UnitOfWork;
use Joomla\ORM\UnitOfWork\UnitOfWorkInterface;
use Joomla\Tests\Unit\ORM\Mocks\Detail;
use Joomla\Tests\Unit\ORM\Mocks\Extra;
use PHPUnit\Framework\TestCase;

class RelationTestCases extends TestCase
{
	/** @var  array */
	protected $config;

	/** @var  RepositoryInterface[] */
	protected $repo;

	/** @var EntityBuilder The entity builder */
	protected $builder;

	/** @var  IdAccessorRegistry */
	protected $idAccessorRegistry;

	/** @var  TransactionInterface */
	protected $transactor;

	/** @var  UnitOfWorkInterface */
	protected $unitOfWork;

	/** @var  EntityRegistry */
	protected $entityRegistry;

	public function setUp()
	{
		$this->idAccessorRegistry = new IdAccessorRegistry;

		$changeTracker  = new ChangeTracker;
		$this->entityRegistry = new EntityRegistry($this->idAccessorRegistry, $changeTracker);

		$this->unitOfWork = new UnitOfWork(
			$this->entityRegistry,
			$this->idAccessorRegistry,
			$changeTracker,
			$this->transactor
		);

		$strategy          = new RecursiveDirectoryStrategy($this->config['definitionPath']);
		$locator           = new Locator([$strategy]);
		$repositoryFactory = new RepositoryFactory($this->config, $this->transactor);
		$this->builder     = new EntityBuilder($locator, $this->config, $this->idAccessorRegistry, $repositoryFactory);
	}

	/**
	 * Read the Extra of a Detail
	 *
	 * The detail record is read from the database, and a Detail object is created and populated with the data.
	 * The virtual extra property is populated with an Extra object (if existent).
	 *
	 * @testdox hasOne: Read the Extra of a Detail
	 */
	public function testReadTheExtraOfADetail()
	{
		$repo   = $this->repo[Detail::class];
		$detail = $repo->getById(1);

		$this->assertInstanceOf(Extra::class, $detail->extra);
		$this->assertEquals('Extra info for Detail 1', $detail->extra->info);
	}

	/**
	 * Create an Extra for a Detail
	 *
	 * Since the detail was fetched using the Repository, the object is known to the ORM.
	 * Its changes are tracked internally, and written to disk automatically.
	 *
	 * @testdox hasOne: Create an Extra for a Detail
	 */
	public function testCreateAnExtraForADetail()
	{
		$repo   = $this->repo[Detail::class];
		$detail = $repo->getById(2);

		$this->assertFalse(isset($detail->extra), 'Detail record #2 should not have an initial Extra record.');

		$detail->extra = new Extra('New info for Detail 2');

		$this->unitOfWork->commit();

		$this->assertEquals('New info for Detail 2', $repo->getById(2)->extra->info);
	}

	/**
	 * Update the extra of a detail
	 * 
	 * The system will detect the change and save just the extra.
	 *
	 * @testdox Update the Extra of a Detail
	 */
	private function testUpdateTheExtraOfADetail()
	{
		$repo   = $this->repo[Detail::class];
		$detail = $repo->getById(1);

		$this->assertEquals(EntityStates::REGISTERED, $this->entityRegistry->getEntityState($detail), 'Detail state before');
		$this->assertEquals(EntityStates::REGISTERED, $this->entityRegistry->getEntityState($detail->extra), 'Extra state before');

		$detail->extra->info = 'Changed information';

		$this->unitOfWork->commit();

		$this->assertEquals('', $this->entityRegistry->getEntityState($detail), 'Detail state after');
		$this->assertEquals('', $this->entityRegistry->getEntityState($detail->extra), 'Extra state after');

		$this->assertEquals('Changed information', $repo->getById(1)->extra->info);
	}

	/**
	 * @param \Exception $e
	 *
	 * @return string
	 */
	protected function dump($e)
	{
		$msg           = '';
		$fmt           = "%s in %s(%d)\n";
		$traceAsString = '';

		while ($e instanceof \Exception)
		{
			$message       = $e->getMessage();
			$file          = $e->getFile();
			$line          = $e->getLine();
			$traceAsString = $e->getTraceAsString();
			$e             = $e->getPrevious();

			$msg .= sprintf($fmt, $message, $file, $line);
		}

		return $msg . "\n" . $traceAsString;
	}
}
