<?php
/**
 * Part of the Joomla Framework ORM Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\ORM\Service;

use Joomla\Cms\ContainerFactory;
use Joomla\Service\QueryHandler;

/**
 * RepositoryFactoryQueryHandler class
 *
 * @package     Joomla\ORM
 * @since       __DEPLOY_VERSION__
 */
class RepositoryFactoryQueryHandler extends QueryHandler
{
	public function handle(RepositoryFactoryQuery $query)
	{
		$container = ContainerFactory::getInstance();

		return $container->get('Repository');
	}
}
