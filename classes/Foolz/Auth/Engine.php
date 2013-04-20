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
	 * Shorthand to get the connection
	 *
	 * @return  \Doctrine\DBAL\Connection
	 */
	public function getConnection()
	{
		return $this->getConfig()->getConnection();
	}

	/**
	 * Returns an instance of the Doctrine DBAL Query Builder
	 *
	 * @return  \Doctrine\DBAL\Query\QueryBuilder
	 */
	public function getQB()
	{
		return $this->getConnection()->createQueryBuilder();
	}

	public function login()
	{
		$user = $this->getQB()
			->select('*')
			->from($this->getConfig()->getUsersTable(), 'u')
			->where('u.id = :id')
			->setParameter(':id', $this->getConfig()->getId())
			->execute()
			->fetch();

		if ( ! $user)
		{
			throw new \Foolz\Auth\Exception\UserNotFound('The user was not found with the supplied data.');
		}

		$this->getConfig()->setJson($user['json']);

		$this->setAutoLogin();
	}

	/**
	 * Login the user with the inbuilt autologin system
	 *
	 * @throws Exception\UserNotFound
	 * @return  int
	 */
	public function autoLogin()
	{
		$result = $this->getConnection()
			->createQueryBuilder()
			->select('*')
			->from($this->getConfig()->getAutoLoginTable(), 'th')
			->where('th.user_id = :user_id')
			->andWhere('th.hash = :hash')
			->andWhere('th.created > :time - 259200')
			->setParameters([
				':user_id' => $this->getConfig()->getLoginHashUserId(),
				':hash_light' => $this->getConfig()->getLoginHashLight(),
				':time' => time()
			])
			->execute()
			->fetch();


		if ($result && password_verify($this->getConfig()->getAutoLoginHash(), $result['login_hash']))
		{
			$this->getConfig()->setId($result['user_id']);
			return static::login();
		}

		throw new \Foolz\Auth\Exception\UserNotFound('No user with this login hash.');
	}

	public function setAutoLogin()
	{
		$key = sha1(uniqid(time().$this->getConfig()->getId()));

		$this->getConfig()->setAutoLoginHash($key);

		$this->getConnection()->insert($this->getConfig()->getAutoLoginTable() ,[
			'user_id' => $this->getConfig()->getId(),
			'hash' => $key,
			'created' => new \DateTime(),
			'ip' => isset($_SERVER['REMOTE_ADDR']) ? \Foolz\Inet\Inet::ptod($_SERVER['REMOTE_ADDR']) : null,
			'agent' => isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null
		]);
	}

	/**
	 * Creates a new user entry and returns its user_id
	 */
	public function createUser()
	{
		$this->getConnection()
			->insert($this->getConfig()->getLoginTable(), [
				'json' => null
			]);

		return $this->getConnection()->lastInsertId();
	}

	/**
	 * Removes the user from the users table
	 */
	public function deleteUser()
	{
		$this->getConnection()
			->createQueryBuilder()
			->delete($this->getConfig()->getUsersTable(), 'u')
			->where('id = :id')
			->setParameter(':id', $this->getConfig()->getId())
			->execute();
	}
}