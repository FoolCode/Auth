## Initialization code
```
<?php

$auth_config = new \Foolz\Auth\Config\Local();

$auth_config->setConnection(DC::forge());
$auth_config->setUsersTable(DC::p('users'));
$auth_config->setAutoLoginTable(DC::p('autologin'));

\Foolz\Auth\Auth::instantiate($auth_config);



AUTOLOGIN TABLE

id
user_id
hash_light
hash
created
ip
agent

USERS TABLE

user_id
json

LOCAL USERS TABLE

id
user_id
username
email
password
created
activated