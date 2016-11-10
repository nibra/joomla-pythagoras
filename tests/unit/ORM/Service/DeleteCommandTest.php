<?php
namespace Joomla\Tests\Unit\ORM\Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Joomla\Cms\ContainerFactory;
use Joomla\Event\Dispatcher;
use Joomla\Event\Event;
use Joomla\ORM\Entity\EntityBuilder;
use Joomla\ORM\Entity\EntityRegistry;
use Joomla\ORM\IdAccessorRegistry;
use Joomla\ORM\Repository\Repository;
use Joomla\ORM\Repository\RepositoryInterface;
use Joomla\ORM\Service\DeleteCommand;
use Joomla\ORM\Service\RepositoryFactory;
use Joomla\ORM\Storage\Doctrine\DoctrineDataMapper;
use Joomla\ORM\Storage\Doctrine\DoctrineTransactor;
use Joomla\ORM\UnitOfWork\TransactionInterface;
use Joomla\ORM\UnitOfWork\UnitOfWorkInterface;
use Joomla\Service\CommandBus;
use Joomla\Service\CommandBusBuilder;
use Joomla\Tests\Unit\DataTrait;
use Joomla\Tests\Unit\ORM\Mocks\Master;

class DeleteCommandTest extends \PHPUnit_Framework_TestCase
{
    /** @var  array */
    private $config;

    /** @var  Connection */
    private $connection;

    /** @var  TransactionInterface */
    private $transactor;

    /** @var  CommandBus */
	private $commandBus;

	/** @var  Dispatcher|\PHPUnit_Framework_MockObject_MockObject */
	private $dispatcher;

    use DataTrait;

    public function setUp()
    {
        $dataPath = realpath(__DIR__ . '/..');

        $this->config = parse_ini_file($dataPath . '/data/entities.doctrine.ini', true);

        $this->connection = DriverManager::getConnection(['url' => $this->config['databaseUrl']]);
        $this->transactor = new DoctrineTransactor($this->connection);

        $repositoryFactory = new RepositoryFactory($this->config, $this->connection, $this->transactor);

        $container = ContainerFactory::getInstance();
        $container->set('Repository', $repositoryFactory, true);
        $this->dispatcher = new Dispatcher;
        $this->commandBus = (new CommandBusBuilder($this->dispatcher))->getCommandBus();
    }

    public function tearDown()
    {
    }

    public function testDelete()
	{
        $this->restoreData(['articles']);

		$expected = [
			'onDeleteEntity'       => 1,
			'onEntityDeleted'      => 1,
			'onDeleteEntityFailed' => [],
		];
		$calls    = [
			'onDeleteEntity'       => 0,
			'onEntityDeleted'      => 0,
			'onDeleteEntityFailed' => [],
		];

		$this->dispatcher->addListener('onDeleteEntity', function (Event $event) use (&$calls)
		{
			$calls['onDeleteEntity']++;
		});

		$this->dispatcher->addListener('onEntityDeleted', function (Event $event) use (&$calls)
		{
			$calls['onEntityDeleted']++;
		});

		$this->dispatcher->addListener('onDeleteEntityFailed', function (Event $event) use (&$calls)
		{
			$calls['onDeleteEntityFailed'][] = $event->getArgument('exception')->getMessage();
		});

		$command = new DeleteCommand('master', ['id=1']);
		$this->commandBus->handle($command);

		$this->assertEquals($expected, $calls);
	}
}
