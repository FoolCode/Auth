<?php

namespace Foolz\Auth;

class User
{
	public $id = null;

	public $username = null;

	public $email = null;

	public $last_ips = null;

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
}