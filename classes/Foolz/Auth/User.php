<?php

namespace Foolz\Auth;

class User
{
	/**
	 * Auth configuration object
	 *
	 * @var  \Foolz\Auth\Config
	 */
	protected $config = null;

	/**
	 * The user ID
	 *
	 * @var  int
	 */
	public $id = null;

	/**
	 * The username
	 *
	 * @var  string
	 */
	public $username = null;

	/**
	 * The email
	 *
	 * @var  string
	 */
	public $email = null;

	/**
	 * The profile array data  (stored as json)
	 *
	 * @var  array
	 */
	public $profile = null;

	/**
	 * Simply returns an empty user that has no special rights
	 *
	 * @return  \Foolz\Auth\User
	 */
	public static function forgeGuest()
	{
		return new static();
	}

	/**
	 * Set the ID
	 *
	 * @param  int  $id  The ID
	 *
	 * @return  \Foolz\Auth\User  The current object
	 */
	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * Return the ID
	 *
	 * @return  int  The ID
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Set the username
	 *
	 * @param  string  $username  The username
	 *
	 * @return  \Foolz\Auth\User  The current object
	 */
	public function setUsername($username)
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * Return the username
	 *
	 * @return  string  The username
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Set the password
	 *
	 * @param  string  $password  The password
	 *
	 * @return \Foolz\Auth\User  The current object
	 */
	public function setPassword($password)
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * Get the password
	 *
	 * @return  string  The password
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Set the email
	 *
	 * @param  string  $email  The email
	 *
	 * @return  \Foolz\Auth\Config\Local  The current object
	 */
	public function setEmail($email)
	{
		$this->email = $email;

		return $this;
	}

	/**
	 * Returns the email
	 *
	 * @return  string  The email
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * Returns the row by email
	 *
	 * @return  array|false  Array if found, false if not found
	 */
	public static function getById($id)
	{
		return $this->getBy('id', $id);
	}

	/**
	 * Returns the row by email
	 *
	 * @return  array|false  Array if found, false if not found
	 */
	public static function getByUsername($username)
	{
		return $this->getBy('username', $this->getUsername());
	}

	/**
	 * Returns the row by email
	 *
	 * @return  array|false  Array if found, false if not found
	 */
	public static function getByEmail($email)
	{
		return $this->getBy('email', $email);
	}

	/**
	 * Return a row by a chosen field
	 *
	 * @param  string  $field  The column to search in
	 * @param  string  $value  The value to search
	 *
	 * @return  array|false  Array if found, false if not found
	 */
	public static function getUserRowBy($field, $value)
	{
		return $this->getQB()
			->select('*')
			->from($this->getConfig()->getUsersTable(), 't')
			->where('t.'.$field.' = :value')
			->setParameter(':value', $value)
			->execute()
			->fetch();
	}

	/**
	 * Returns a row by username or email
	 *
	 * @param  string  $username_or_email
	 *
	 * @return  array|false  Array if found, false if not found
	 */
	public static function getByUsernameOrEmail($username_or_email)
	{
		return $this->getQB()
			->select('*')
			->from($this->getConfig()->getUsersTable(), 't')
			->where('t.username = :username')
			->orWhere('t.email = :email')
			->setParameter(':username', $username_or_email)
			->setParameter(':email', $username_or_email)
			->execute()
			->fetch();
	}
}