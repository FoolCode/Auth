<?php

namespace Foolz\Auth;

class Engine
{
	/**
	 * The configuration for the Engine instance
	 *
	 * @var  \Foolz\Auth\Config
	 */
	public $config = null;

	/**
	 * Returns the configuration for the Engine instance
	 *
	 * @param  \Foolz\Auth\Config  $config  The configuration object
	 *
	 * @return  \Foolz\Auth\Engine  The current object
	 */
	public function setConfig($config)
	{
		$this->config = $config;

		return $this;
	}

	/**
	 * Return the config object
	 *
	 * @return  \Foolz\Auth\Config  The configuration object
	 */
	public function getConfig()
	{
		return $this->config;
	}

	/**
	 * Login the user with the inbuilt autologin system
	 *
	 * @return  int
	 */
	public function autoLogin()
	{
		$result = $this->getQB()
			->select('*')
			->from($this->getConfig()->getAutoLoginTable(), 'th')
			->where('id = :login_hash_id')
			->where('created > :time + 259200')
			->setParameters([
				':login_hash_id' => $this->getConfig()->getLoginHashId(),
				':time' => time()
			])
			->execute()
			->fetch();

		if ($result && password_verify($this->getConfig()->getLoginHash(), $result['login_hash']))
		{
			return $result['user_id'];
		}

		throw new \Foolz\Auth\Exception\UserNotFound;
	}

	public function setAutoLogin($user)
	{
		sha1(uniqid(time().$user->id));
	}

	public function register()
	{
		throw new \Foolz\Auth\Exception\Misconfiguration('Invalid call if a specific engine isn\'t set and the method is not overridden.');
	}

	public function delete()
	{

	}
}