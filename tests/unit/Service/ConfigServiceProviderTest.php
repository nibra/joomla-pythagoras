<?php
namespace Joomla\Tests\Unit\Service;
use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;
use Joomla\Registry\Registry;
use Joomla\Service\ConfigServiceProvider;

class ConfigServiceProviderTest extends \PHPUnit_Framework_TestCase
{

	/**
	 * @testdox The ConfigServiceProvider implements the
	 * ServiceProviderInterface interface
	 */
	public function testTheTestConfigServiceProviderImplementsTheServiceProviderInterface ()
	{
		$this->assertInstanceOf(ServiceProviderInterface::class, new ConfigServiceProvider(__DIR__ . '/data', 'env.txt'));
	}

	/**
	 * @testdox The ConfigServiceProvider adds an config to a container
	 */
	public function testConfigServiceProviderCreatesConfig ()
	{
		$container = new Container();

		$service = new ConfigServiceProvider(__DIR__ . '/data', 'env.txt');
		$service->register($container);

		$this->assertInstanceOf(Registry::class, $container->get('config'));
	}

	/**
	 * @testdox The ConfigServiceProvider adds an config to a
	 * container with variables from the environment
	 */
	public function testConfigServiceProviderCreatesConfigFromEnv ()
	{
		$container = new Container();

		$service = new ConfigServiceProvider(__DIR__ . '/data', 'env.txt');
		$service->register($container);

		/** @var Registry $config **/
		$config = $container->get('config');

		$this->assertEquals('bar', $config->get('foo'));
	}
}
