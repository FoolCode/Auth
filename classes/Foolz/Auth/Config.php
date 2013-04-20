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
	 * The user ID of the current login hash
	 *
	 * @var  int
	 */
	public $auto_login_hash_user_id = null;

	/**
	 * The auto login hash (not the same as database, where it's hashed)
	 *
	 * @var  string
	 */
	public $auto_login_hash = null;

	/**
	 * The auto login hash light that doesn't get hashed in database
	 *
	 * @var  string
	 */
	public $auto_login_hash_light = null;

	/**
	 * The table for auto login hashes
	 *
	 * @var  string
	 */
	public $auto_login_table = null;

	/**
	 * The user ID
	 *
	 * @var  int
	 */
	protected $id = null;

	/**
	 * The json array
	 *
	 * @var null|array
	 */
	public $json = null;

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
	 * @param  int  $auto_login_hash_user_id  The login hash ID
	 *
	 * @return  \Foolz\Auth\Config  The current object
	 */
	public function setAutoLoginHashUserId($auto_login_hash_user_id)
	{
		$this->auto_login_hash_id = $auto_login_hash_user_id;

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
	 * Returns the login_hash_id
	 *
	 * @return  int  The login hash ID
	 */
	public function getAutoLoginHashLight()
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
	public function setAutoLoginHashLight($auto_login_hash_light)
	{
		$this->auto_login_hash_light = $auto_login_hash_light;

		return $this;
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

	/**
	 * Set the user ID
	 *
	 * @param  int  $id  The user ID
	 *
	 * @return  \Foolz\Auth\Config\Local  The current object
	 */
	public function setId($id)
	{
		$this->id = $id;

		return $this;
	}

	/**
	 * Return the user ID
	 *
	 * @return  int  The user ID
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * Decodes the JSON and sets it as associative array
	 *
	 * @param array $json
	 *
	 * @return $this
	 */
	public function setJson($json)
	{
		if ($json !== null)
		{
			$this->json = json_decode($json, true);
		}
		else
		{
			$this->json = null;
		}

		return $this;
	}

	/**
	 * @return array
	 */
	public function getJson()
	{
		return $this->json;
	}
}