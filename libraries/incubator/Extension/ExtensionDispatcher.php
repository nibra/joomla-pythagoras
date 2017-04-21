<?php
/**
 * Part of the Joomla Framework Extension Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Extension;

use Joomla\Event\Dispatcher;
use Joomla\Event\EventInterface;

/**
 * Class ExtensionDispatcher
 *
 * Lazy loading extension dispatcher, which loads the listeners from a
 * ExtensionFactoryInterface when the event is fired the first time.
 *
 * @package  Joomla\Extension
 *
 * @since    __DEPLOY_VERSION__
 */
class ExtensionDispatcher extends Dispatcher
{
	/** @var ExtensionFactoryInterface The extension factory */
	private $factory;

	/** @var string[] The loaded events */
	private $loadedEvents = [];

	/**
	 * ExtensionDispatcher constructor.
	 *
	 * @param   ExtensionFactoryInterface $factory The extension factory
	 */
	public function __construct(ExtensionFactoryInterface $factory)
	{
		parent::__construct();

		$this->factory = $factory;
	}

	/**
	 * Call the extensions
	 *
	 * @param   EventInterface $event The event
	 *
	 * @return  EventInterface
	 */
	public function dispatch(EventInterface $event)
	{
		$this->logger->debug(__METHOD__ . ": Dispatching " . $event->getName());

		$name = $event->getName();

		if (!key_exists($name, $this->loadedEvents))
		{
			$this->logger->debug(__METHOD__ . ": - Loading extension listeners");

			$this->loadExtensionListeners($name, $this->factory->getExtensions());
			$this->loadedEvents[$name] = $name;
		}

		$result = parent::dispatch($event);
		$this->logger->debug(__METHOD__ . ": Done.");

		return $result;
	}

	/**
	 * Loads the listeners from the given extensions and attaches them.
	 *
	 * @param   string               $name       The event name
	 * @param   ExtensionInterface[] $extensions A list of extensions
	 *
	 * @return  void
	 */
	private function loadExtensionListeners($name, array $extensions)
	{
		foreach ($extensions as $extension)
		{
			foreach ($extension->getListeners($name) as $listener)
			{
				$this->addListener($name, $listener);
			}
		}
	}
}
