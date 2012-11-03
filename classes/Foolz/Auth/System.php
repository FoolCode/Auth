<?php

namespace Foolz\Auth;

abstract class System
{
	public $config = null;

	public function setConfig($config)
	{
		$this->config = $config;
	}

	public abstract function login();

	public abstract function autoLogin();

	public abstract function register();

	public abstract function delete();

	public abstract function changeData($field);
}