<?php
namespace Joomla\Tests\Unit\ORM\Service;

use Joomla\Event\Dispatcher;
use Joomla\Event\Event;
use Joomla\ORM\Service\DeleteCommand;
use Joomla\Service\CommandBus;
use Joomla\Service\CommandBusBuilder;

class DeleteCommandTest extends \PHPUnit_Framework_TestCase
{
	/** @var  CommandBus */
	private $commandBus;

	/** @var  Dispatcher|\PHPUnit_Framework_MockObject_MockObject */
	private $dispatcher;

	public function setUp()
	{
		$this->dispatcher = $this->createMock(Dispatcher::class);
		$this->commandBus = (new CommandBusBuilder($this->dispatcher))->getCommandBus();
	}

	public function testDelete()
	{
		$expected = [
			'onDeleteEntity'       => 1,
			'onEntityDeleted'      => 1,
			'onDeleteEntityFailed' => 0,
		];
		$calls    = [
			'onDeleteEntity'       => 0,
			'onEntityDeleted'      => 0,
			'onDeleteEntityFailed' => 0,
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
			$calls['onDeleteEntityFailed']++;
		});

		$command = new DeleteCommand('article', ['id=1']);
		$this->commandBus->handle($command);

		$this->assertEquals($expected, $calls);
	}
}
