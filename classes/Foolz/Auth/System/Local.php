<?php

namespace Foolz\Auth;

class Local
{
	/**
	 *
	 * @return  \Foolz\Auth\Config\Local $config
	 */
	public function getConfig()
	{
		return $this->config;
	}

	public function login()
	{

		if ($this->getConfig()->getId() !== null)
		{
			// "forced login"
		}
		elseif ($this->getConfig()->getUsername() !== null
			&& $this->getConfig()->getPassword() !== null)
		{
			// login by username
		}
		elseif ($this->getConfig()->getEmail() !== null
			&& $this->getConfig()->getPassword() !== null)
		{
			// login by email
		}
		elseif ($this->getConfig()->getUsernameOrEmail() !== null
			&& $this->getConfig()->getPassword() !== null)
		{

		}

	}

	public function autoLogin()
	{
		if ($this->getConfig()->getLoginHash())
		{
			
		}
	}



}