<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Frontcontroller
 * @group       Frontcontroller
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Class EntryPointTest
 *
 * @since 4.0
 */
class EntryPointTest extends PHPUnit_Framework_TestCase
{
	/**
	 * This method is called before the first test of this test class is run.
	 */
	public static function setUpBeforeClass()
	{
		require_once 'libraries/joomla/factory.php';
	}

	/**
	 * Sets up the fixture, for example, open a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
	}

	/**
	 * Performs assertions shared by all tests of a test case.
	 *
	 * This method is called before the execution of a test starts
	 * and after setUp() is called.
	 */
	protected function assertPreConditions()
	{
	}

	/**
	 * Performs assertions shared by all tests of a test case.
	 *
	 * This method is called before the execution of a test ends
	 * and before tearDown() is called.
	 */
	protected function assertPostConditions()
	{
	}

	/**
	 * Tears down the fixture, for example, close a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

	/**
	 * This method is called after the last test of this test class is run.
	 */
	public static function tearDownAfterClass()
	{
	}

	/**
	 * @since   4.0
	 */
	public function casesEntryPoints()
	{
		return [
			'cli'   => [
				'cli',
				'cli/joomla.php',
				'JApplicationCli'
			],
			'api'   => [
				'api',
				'api.php',
				'JApplicationApi'
			],
			'web'   => [
				'site',
				'index.php',
				'JApplicationSite'
			],
			'admin' => [
				'administrator',
				'administrator/index.php',
				'JApplicationAdministrator'
			],
		];
	}

	/**
	 * @dataProvider casesEntryPoints
	 *
	 * @param string $client
	 * @param string $entryPoint
	 * @param string $applicationType
	 *
	 * @since        4.0
	 */
	public function testEntryPointsExist($client, $entryPoint, $applicationType)
	{
		$this->assertFileExists($entryPoint);
	}
}
