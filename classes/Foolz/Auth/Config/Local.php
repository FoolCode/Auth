<?php

namespace Foolz\Auth\Config;

class Local
{
	public $id = null;

	public $username = null;

	public $password = null;

	public $email = null;

	public $username_or_email = null;

	public function setId();

	public function getId();

	public function setUsername();

	public function getUsername();

	public function setPassword();

	public function getPassword();

	public function setEmail();

	public function getEmail();

	public function setUsernameOrEmail();

	public function getUsernameOrEmail();
}