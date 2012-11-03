<?php

namespace Foolz\Auth\Config;

/**
 * Configuration class for local, Doctrine2-powered authentication
 */
class Local
{
	/**
	 * The user ID
	 *
	 * @var  int
	 */
	protected $id = null;

	/**
	 * The username
	 *
	 * @var  string
	 */
	protected $username = null;

	/**
	 * The password
	 *
	 * @var  string
	 */
	protected $password = null;

	/**
	 * The email
	 *
	 * @var  string
	 */
	protected $email = null;

	/**
	 * Either the username or the email, so the login can be done with either
	 *
	 * @var  string
	 */
	protected $username_or_email = null;

	/**
	 * The login table name
	 *
	 * @var  string
	 */
	protected $login_table = null;

	/**
	 * Set the ID
	 *
	 * @param  int  $id  The ID
	 *
	 * @return  \Foolz\Auth\Config\Local  The current object
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
	 * @return  \Foolz\Auth\Config\Local  The current object
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
	 * @return \Foolz\Auth\Config\Local  The current object
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
	 * Set a string that may be the username or the email
	 *
	 * @param  string  $username_or_email  Username or email
	 *
	 * @return  \Foolz\Auth\Config\Local  The current object
	 */
	public function setUsernameOrEmail($username_or_email)
	{
		$this->username_or_email = $username_or_email;

		return $this;
	}

	/**
	 * Return the value that might be an username or an email
	 *
	 * @return  string  The username or email
	 */
	public function getUsernameOrEmail()
	{
		return $this->username_or_email;
	}

	/**
	 * The login table name
	 *
	 * @param  string  $table_name  The table name
	 *
	 * @return  \Foolz\Auth\Config\Local  The current object
	 */
	public function setLoginTable($table_name)
	{
		$this->login_table = $table_name;

		return $this;
	}

	/**
	 * Returns the login table name
	 *
	 * @return  string  The table name
	 */
	public function getLoginTable()
	{
		return $this->login_table;
	}
}