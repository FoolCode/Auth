<?php

namespace Foolz\Auth;

class Auth
{
	/**
	 * The engine used to fetch the user
	 *
	 * @var  \Foolz\Auth\Engine
	 */
	protected $engine = null;

	/**
	 * Associative array of configuration instances
	 *
	 * @var  \Foolz\Auth\Config[]
	 */
	protected $instances = [];

	/**
	 * Creates a new instance with the given config
	 *
	 * @param  \Foolz\Auth\Config  $config         The configuration
	 * @param  string              $instance_name
	 */
	public static function instantiate(\Foolz\Auth\Config $config, $instance_name = 'default')
	{
		static::$instances[$instance_name] = $config;
	}

	public static function forge($instance_name)
	{
		if ( ! isset(static::$instances[$instance_name]))
		{
			throw new \OutOfRangeException('The instance specified doesn\'t exist');
		}

		return static::$instances[$instance_name];
	}

	/**
	 * Sets a configuration
	 *
	 * @param  \Foolz\Auth\Config  $config  The configuration object
	 *
	 * @return  \Foolz\Auth\Auth  The current object
	 */
	public function setConfig(\Foolz\Auth\Config $config)
	{
		$this->config = $config;

		return $this;
	}

	/**
	 * Returns the config
	 *
	 * @return  \Foolz\Auth\Config  The configuration
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * Returns the storage engine object or creates it if necessary
	 *
	 * @return  \Foolz\Auth\Engine  The configured Storage object
	 */
	public function getEngine()
	{
		if ($this->engine === null)
		{
			$class = '\Foolz\Auth\Engine\\'.Util::lowercaseToClassName($this->getConfig()->getEngine());

			$this->engine = new $class();
			$this->engine->setConfig($this->getConfig());
		}

		return $this->engine;
	}
}
