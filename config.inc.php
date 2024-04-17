<?php
// site root path
define('__TYPECHO_ROOT_DIR__', dirname(__FILE__));

// plugin directory (relative path)
define('__TYPECHO_PLUGIN_DIR__', '/usr/plugins');

// theme directory (relative path)
define('__TYPECHO_THEME_DIR__', '/usr/themes');

// admin directory (relative path)
define('__TYPECHO_ADMIN_DIR__', '/admin/');

// register autoload
require_once __TYPECHO_ROOT_DIR__ . '/var/Typecho/Common.php';

// init
\Typecho\Common::init();

// config db
$db = new \Typecho\Db($_ENV["TYPECHO_ADAPTER_NAME"], $_ENV["TYPECHO_PREFIX"]);
$db->addServer(array (
    'host' => $_ENV["TYPECHO_HOST"],
    'port' => $_ENV["TYPECHO_PORT"],
    'user' => $_ENV["TYPECHO_USERNAME"],
    'password' => $_ENV["TYPECHO_PASSWORD"],
    'charset' => $_ENV["TYPECHO_CHARSET"],
    'database' => $_ENV["TYPECHO_NAME"],
    'engine' => $_ENV["TYPECHO_ENGINE"],
    'sslCa' => $_ENV["TYPECHO_SSL_CA"],
    'sslVerify' => true,
), \Typecho\Db::READ | \Typecho\Db::WRITE);
\Typecho\Db::set($db);
