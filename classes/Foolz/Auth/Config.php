<?php

namespace Foolz\Auth;

class Config
{
	/**
	 * The Doctrine DBAL Connection
	 *
	 * @var  \Doctrine\DBAL\Connection
	 */
	public $connection = null;

	/**
	 * The table for the users
	 *
	 * @var  string
	 */
	public $users_table = null;

	/**
	 * The ID of the current login hash
	 *
	 * @var  int
	 */
	public $auto_login_hash_id = null;

	/**
	 * The auto login hash (not the same as database, where it's hashed)
	 *
	 * @var  string
	 */
	public $auto_login_hash = null;

	/**
	 * The table for auto login hashes
	 *
	 * @var  string
	 */
	public $auto_login_table = null;

	/**
	 * Set a Doctrine DBAL Connection
	 *
	 * @param  \Doctrine\DBAL\Connection  $connection  The connection
	 *
	 * @return  \Foolz\Auth\Config  The current object
	 */
	public function setConnection(\Doctrine\DBAL\Connection $connection)
	{
		$this->connection = $connection;

		return $this;
	}

	/**
	 * Returns the Doctrine DBAL Connection
	 *
	 * @return  \Doctrine\DBAL\Connection  The connection
	 * @throws  \BadMethodCallException    If the connection wasn't set
	 */
	public function getConnection()
	{
		if ($this->connection === null)
		{
			throw new \BadMethodCallException('The connection wasn\'t set.');
		}

		return $this->connection;
	}

	/**
	 * Set the users database table
	 *
	 * @param  string  $table_name  The name of the table
	 *
	 * @return  \Foolz\Auth\Config  The current object
	 */
	public function setUsersTable($table_name)
	{
		$this->users_table = $table_name;

		return $this;
	}

	/**
	 * Get the name of the users table
	 *
	 * @return  string  The table name
	 */
	public function getUsersTable()
	{
		return $this->users_table;
	}

	/**
	 * For speed, we also use the login_hash_id so we can check against a single one
	 *
	 * @param  int  $auto_login_hash_id  The login hash ID
	 *
	 * @return  \Foolz\Auth\Config  The current object
	 */
	public function setAutoLoginHashId($auto_login_hash_id)
	{
		$this->auto_login_hash_id = $auto_login_hash_id;

		return $this;
	}

	/**
	 * Returns the login_hash_id
	 *
	 * @return  int  The login hash ID
	 */
	public function getAutoLoginHashId()
	{
		return $this->auto_login_hash_id;
	}

	/**
	 * Set the login hash for automatic login
	 *
	 * @param  string  $auto_login_hash  The login hash before database hashing
	 *
	 * @return  \Doctrine\DBAL\Connection  The connection
	 */
	public function setAutoLoginHash($auto_login_hash)
	{
		$this->auto_login_hash = $auto_login_hash;

		return $this;
	}

	/**
	 * Returns the login_hash
	 *
	 * @return  string  The login hash before database hashing
	 */
	public function getAutoLoginHash()
	{
		return $this->auto_login_hash;
	}

	/**
	 * Sets the name of the auto login table
	 *
	 * @param  string  $table_name  The table name
	 *
	 * @return  \Foolz\Auth\Config  The current object
	 */
	public function setAutoLoginTable($table_name)
	{
		$this->auto_login_table = $table_name;

		return $this;
	}

	/**
	 * Returns the auto login table name
	 *
	 * @return  string  The table name
	 */
	public function getAutoLoginTable()
	{
		return $this->auto_login_table;
	}
}