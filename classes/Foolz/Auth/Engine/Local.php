<?php

namespace Foolz\Auth\Engine;

/**
 * Doctrine2-powered local login system
 */
class Local extends \Foolz\Auth\System
{
	/**
	 * Get the configuration object
	 *
	 * @return  \Foolz\Auth\Config\Local $config
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

	/**
	 * Logins the user with any of the given
	 *
	 * @return  \Foolz\Auth\User\Local
	 */
	public function getUser()
	{
		$user = null;

		if ($this->getConfig()->getId() !== null)
		{
			// get user by ID

			$user = $this->getUserById();

			return $this->forgeUser($user);
		}
		elseif ($this->getConfig()->getPassword())
		{
			// this is an actual login system in disguise

			if ($this->getConfig()->getUsername() !== null)
			{
				// login by username
				$user = $this->getUserByUsername();
			}
			elseif ($this->getConfig()->getEmail() !== null)
			{
				// login by email
				$user = $this->getUserByEmail();
			}
			elseif ($this->getConfig()->getUsernameOrEmail() !== null)
			{
				// login by either username or email
				$user = $this->getUserByUsernameOrEmail();
			}

			if ($user === null)
			{
				throw new \Foolz\Auth\Exception\Misconfiguration('No valid login input has been given.');
			}

			if (password_verify($this->getConfig()->getPassword(), $user['password']))
			{
				if (password_needs_rehash($user['password'], PASSWORD_DEFAULT))
				{
					$this->setPassword();
				}

				return $this->forgeUser($user);
			}
		}

		if ($user === null)
		{
			throw new \Foolz\Auth\Exception\Misconfiguration('No valid login input has been given.');
		}
	}

	public function autoLogin()
	{
		if ($this->getConfig()->getLoginHash())
		{

		}
	}

	public function forgeUser($database_array)
	{
		return \Foolz\Auth\User\Local::forgeFromDatabase($database_array);
	}

	public function getUserById($id)
	{

	}

	public function getUserRowById()
	{
		return $this->getUserRowBy('id', $this->getConfig()->getId());
	}

	public function getUserRowByUsername()
	{
		return $this->getUserRowBy('username', $this->getConfig()->getUsername());
	}

	public function getUserRowByEmail()
	{
		return $this->getUserRowBy('email', $this->getConfig()->getEmail());
	}

	public function getUserRowBy($field, $value)
	{
		$result = $this->getQB()
			->select('*')
			->from($this->getConfig()->getTable(), 't')
			->where('t.'.$field.' = :value')
			->setParameter(':value', $value)
			->execute()
			->fetch();

		if ( ! $result)
		{
			throw new \Foolz\Auth\Exception\UserNotFound;
		}

		return $result;
	}

	/**
	 *
	 *
	 * @param type $username
	 * @param type $email
	 * @return type
	 * @throws \Foolz\Auth\Exception\UserNotFound
	 */
	public function getUserRowByUsernameOrEmail()
	{
		$result = $this->getQB()
			->select('*')
			->from($this->getConfig()->getTable(), 't')
			->where('t.username = :username')
			->orWhere('t.email = :email')
			->setParameter(':username', $this->getConfig()->getUsernameOrEmail())
			->setParameter(':email', $this->getConfig()->getUsernameOrEmail())
			->execute()
			->fetch();

		if ( ! $result)
		{
			throw new \Foolz\Auth\Exception\UserNotFound;
		}

		return $result;
	}


}