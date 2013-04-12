## Initialization code
```
<?php

$auth_config = new \Foolz\Auth\Config\Local();

$auth_config->setConnection(DC::forge());
$auth_config->setUsersTable(DC::p('users'));
$auth_config->setAutoLoginTable(DC::p('autologin'));

$auth_config->setAddons([]);

$auth_config->setUsername();

\Foolz\Auth\Auth::instantiate($auth_config);