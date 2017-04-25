<?php
/**
 * Part of the Joomla Framework Renderer Package
 *
 * @copyright  Copyright (C) 2015 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Joomla\Content\Type;

use Joomla\Content\ContentTypeInterface;

/**
 * Abstract ContentType
 *
 * @package  Joomla/Content
 * @since    __DEPLOY_VERSION__
 */
abstract class AbstractContentType implements ContentTypeInterface
{
	/**
	 * Pre-rendered HTML
	 *
	 * @deprecated
	 * @var string
	 */
	public $html = null;

	/** @var  string The identifier */
	private $id;

	/** @var  string The title */
	private $title;

	/** @var  array The parameters */
	private $params;

	/**
	 * AbstractContentType constructor.
	 *
	 * @param string $title  The title
	 * @param string $id     The identifier
	 * @param array  $params Optional parameters
	 */
	public function __construct($title, $id, array $params = [])
	{
		$this
			->setTitle($title)
			->setId($id)
			->setParameters($params);
	}

	/**
	 * @param array $params
	 *
	 * @return AbstractContentType
	 */
	public function setParameters(array $params)
	{
		$this->params = $params;

		return $this;
	}

	public function __get($property)
	{
		if (isset($this->{$property}))
		{
			return $this->{$property};
		}

		throw new \UnexpectedValueException("Property $property not found in " . get_class($this));
	}

	/**
	 * @return string
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param string $id
	 *
	 * @return AbstractContentType
	 */
	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 *
	 * @return AbstractContentType
	 */
	public function setTitle($title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->params;
	}

	/**
	 * @param string $key     The key
	 * @param mixed  $default The default value
	 *
	 * @return mixed
	 */
	public function getParameter($key, $default = null)
	{
		return isset($this->params[$key]) ? $this->params[$key] : $default;
	}
}
