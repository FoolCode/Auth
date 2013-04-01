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
	 * Logins the user with any of the given data
	 *
	 * @return  \Foolz\Auth\User\Local
	 */
	public function login()
	{
		$user = null;

		if ($this->getConfig()->getPassword())
		{
			if ($this->getConfig()->getUsername() !== null)
			{
				// login by username
				$user = \Foolz\Auth\User::getByUsername($this->getConfig()->getUsername());
			}
			elseif ($this->getConfig()->getEmail() !== null)
			{
				// login by email
				$user = \Foolz\Auth\User::getByEmail($this->getConfig()->getEmail());
			}
			elseif ($this->getConfig()->getUsernameOrEmail() !== null)
			{
				// login by either username or email
				$user = \Foolz\Auth\User::getByUsernameOrEmail($this->getConfig()->getUsernameOrEmail());
			}

			if ($user === null)
			{
				throw new \Foolz\Auth\Exception\Misconfiguration('No valid login input has been given.');
			}

			$user_doctrine = $this->getQB()
				->select('*')
				->from($this->getConfig()->getLoginTable(), 't')
				->where('id = :id')
				->setParameter(':id', $user->getId())
				->execute()
				->fetch();

			if ( ! $user_doctrine)
			{
				// the user never logged in with this method
				throw new \Foolz\Auth\Exception\UserNotFound;
			}

			if (password_verify($this->getConfig()->getPassword(), $user_doctrine['password']))
			{
				if (password_needs_rehash($user['password'], PASSWORD_DEFAULT))
				{
					$this->updatePassword($user, $this->getConfig()->getPassword());
				}

				return $user['user_id'];
			}

			throw new \Foolz\Auth\Exception\UserNotFound;
		}

		if ($user === null)
		{
			throw new \Foolz\Auth\Exception\Misconfiguration('No valid login input has been given.');
		}
	}

	/**
	 * Updates the password with a new one, or for a rehash
	 *
	 * @param  \Foolz\Auth\User  $user      The user object to identify the row
	 * @param  string            $password  The password to change to
	 */
	public function updatePassword($user, $password)
	{
		$hash = password_hash($password, PASSWORD_DEFAULT);

		$this->getQB()
			->update($this->getConfig()->getLoginTable())
			->set('password', ':password')
			->where('user_id = :user_id')
			->setParameters([':password' => $hash, ':user_id' => $user->getId()])
			->execute();
	}

	/**
	 * Create an user
	 *
	 * @param  \Foolz\Auth\User  $user  User object
	 *
	 * @return  boolean  True if the line has been created, false on error
	 */
	public function createUser($user)
	{
		$hash = password_hash($this->getConfig()->getPassword(), PASSWORD_DEFAULT);

		$affected = $this->getConnection()
			->insert($this->getConfig()->getLoginTable(), [
				'user_id' => $user->getId(),
				'password' => $hash
			]);

		return $affected === 1;
	}

	/**
	 * Delete an user
	 *
	 * @param  \Foolz\Auth\User  $user  User object
	 *
	 * @return  boolean  True if the line has been created, false on error
	 */
	public function deleteUser($user)
	{
		$affected = $this->getQB()
			->delete($this->getConfig()->getLoginTable())
			->where('user_id = :user_id')
			->setParameter(':user_id', $user->getId())
			->execute();

		return $affected === 1;
	}
}