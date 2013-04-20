<?php

namespace Foolz\Auth\Engine;

/**
 * Doctrine2-powered local login system
 */
class Local extends \Foolz\Auth\Engine
{
	/**
	 * The name of the engine
	 *
	 * @var string
	 */
	public $engine_name = 'local';

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
	 * Logins the user with any of the given data
	 */
	public function login()
	{
		$query = $this->getQB()
			->select('*')
			->from($this->getConfig()->getLoginTable(), 't');

		if ($this->getConfig()->getPassword())
		{
			if ($this->getConfig()->getUsername() !== null)
			{
				$query->where('username = :username')
					->setParameter(':username', $this->getConfig()->getUsername());
			}
			elseif ($this->getConfig()->getEmail() !== null)
			{
				$query->where('email = :email')
					->setParameter(':email', $this->getConfig()->getEmail());
			}
			elseif ($this->getConfig()->getUsernameOrEmail() !== null)
			{
				$query->where('username = :username')
					->setParameter(':username', $this->getConfig()->getUsername())
					->orWhere('email = :email')
					->setParameter(':email', $this->getConfig()->getEmail());
			}

			$user = $query->execute()
				->fetch();

			if ($user && password_verify($this->getConfig()->getPassword(), $user['password']))
			{
				if (password_needs_rehash($user['password'], PASSWORD_DEFAULT))
				{
					$this->updatePassword($user, $this->getConfig()->getPassword());
				}

				if ( ! $user['activated'])
				{
					throw new \Foolz\Auth\Exception\UserNotActivated('The user account hasn\'t been activated yet.');
				}

				$this->getConfig()->setId($user['userid']);
				$this->getConfig()->setUsername($user['username']);
				$this->getConfig()->setEmail($user['email']);
				// get the password off there
				$this->getConfig()->setPassword(null);

				$this->setId($this->getConfig()->getId());
				return parent::login();
			}

			throw new \Foolz\Auth\Exception\UserNotFound('No such user with the supplied data.');
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
	 */
	public function createUser($activated = 0)
	{
		if ($this->getConfig()->getUsername() === null || $this->getConfig()->getEmail() === null
			|| $this->getConfig()->getPassword())
		{
			throw new \Foolz\Auth\Exception\Misconfiguration('Username, Email and Password must be set to create an new user.');
		}

		$this->getConnection()->beginTransaction();

		$id = parent::createUser();

		$hash = password_hash($this->getConfig()->getPassword(), PASSWORD_DEFAULT);

		$activation_key = sha1(uniqid(time().$id));

		$this->getConnection()
			->insert($this->getConfig()->getLoginTable(), [
				'user_id' => $id,
				'username' => $this->getConfig()->getUsername(),
				'email' => $this->getConfig()->getEmail(),
				'password' => $hash,
				'last_login' => 0,
				'created' => new DateTime(),
				'activated' => (int) $activated,
				'activation_key' => $activation_key,
				'new_email' => null,
				'new_email_key' => null,
				'new_email_time' => null,
				'new_password_key' => null,
				'new_password_time' => null,
				'deletion_key' => null,
				'deletion_time' => null
			], [
				\PDO::PARAM_INT,
				\PDO::PARAM_STR,
				\PDO::PARAM_STR,
				\PDO::PARAM_STR,
				'datetime',
			]);

		$this->getConnection()->commit();

		return $activation_key;
	}

	/**
	 * Delete an user
	 *
	 * @return  boolean  True if the line has been created, false on error
	 */
	public function deleteUser()
	{
		$this->getConnection()->beginTransaction();

		parent::deleteUser();

		$this->getQB()
			->delete($this->getConfig()->getLoginTable())
			->where('user_id = :user_id')
			->setParameter(':user_id', $this->getConf)
			->execute();

		$this->getConnection()->commit();
	}

	public function activate($key)
	{
		$affected = $this->getQB()
			->update($this->getConfig()->getLoginTable())
			->set('activated', ':activated')
			->where('user_id = :user_id')
			->andWhere('activation_key = :activation_key')
			->setParameters([
				':user_id' => $this->getConfig()->getId(),
				':activated' => 1,
				':activation_key' => $key
			])
			->execute();

		return $affected === 1;
	}

	public function getNewEmailKey($email)
	{
		// please take care of checking if the email is not bogus before reaching this.
		if ( ! filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			throw new \DomainException('The email submitted is not valid.');
		}

		$key = sha1(uniqid(time().$this->getConfig()->getId()));

		$affected = $this->getQB()
			->update($this->getConfig()->getLoginTable())
			->set('new_email', ':new_email')
			->set('new_email_key', ':new_email_key')
			->set('new_email_time', ':new_email_time')
			->where('user_id = :user_id')
			->andWhere('activation_key = :activation_key')
			->setParameters([
				':user_id' => $this->getConfig()->getId(),
				':new_email_key' => $key,
				':new_email_time' => new \DateTime()
			])
			->execute();

		if ($affected !== 0)
		{
			throw new \UnexpectedValueException('The new email couldn\'t be submitted because of a database error.');
		}

		return $key;
	}

	public function setNewEmailWithKey($key)
	{

	}

	public function getNewPasswordKey()
	{

		return $key;
	}

	public function setNewPasswordWithKey($key)
	{

	}

	public function getDeletionKey()
	{

		return $key;
	}

	public function setDeletionWithKey($key)
	{

	}
}