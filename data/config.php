<?php
// database host
$db_host   = "localhost:3306";

// database name
$db_name   = "project1";

// database username
$db_user   = "root";

// database password
$db_pass   = "ab01";

// table prefix
$prefix    = "yke_";

$timezone    = "Asia/Shanghai";

$cookie_path    = "/";

$cookie_domain    = "";

$session = "1440";

define('EC_CHARSET','utf-8');

if(!defined('ADMIN_PATH'))
{
define('ADMIN_PATH','admin');
}
define('AUTH_KEY', 'this is a key');

define('OLD_AUTH_KEY', '');

define('API_TIME', '2016-04-06 09:17:10');

?>