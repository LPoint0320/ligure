<?php
define('__TYPECHO_ROOT_DIR__', dirname(__FILE__));
define('__TYPECHO_PLUGIN_DIR__', '/usr/plugins');
define('__TYPECHO_THEME_DIR__', '/usr/themes');
define('__TYPECHO_ADMIN_DIR__', '/admin/');
require_once __TYPECHO_ROOT_DIR__ . '/var/Typecho/Common.php';
\Typecho\Common::init();

// Railway / Vercel / cloud env — support multiple env var formats
 = getenv('MYSQL_URL') ?: getenv('DATABASE_URL') ?: '';
if () {
     = parse_url();
    System.Management.Automation.Internal.Host.InternalHost = ['host'] ?? '127.0.0.1';
     = ['port'] ?? 3306;
     = ['user'] ?? 'root';
     = ['pass'] ?? '';
     = ltrim(['path'] ?? '', '/');
} else {
    System.Management.Automation.Internal.Host.InternalHost = getenv('MYSQLHOST') ?: getenv('MYSQL_HOST') ?: '127.0.0.1';
     = getenv('MYSQLPORT') ?: getenv('MYSQL_PORT') ?: 3306;
     = getenv('MYSQLUSER') ?: getenv('MYSQL_USER') ?: 'root';
     = getenv('MYSQLPASSWORD') ?: getenv('MYSQL_PASSWORD') ?: '';
     = getenv('MYSQLDATABASE') ?: getenv('MYSQL_DATABASE') ?: 'typecho';
}

 = new \Typecho\Db('Pdo_Mysql', 'typecho_');
->addServer(array(
  'host' => System.Management.Automation.Internal.Host.InternalHost,
  'port' => intval(),
  'user' => ,
  'password' => ,
  'database' => ,
  'charset' => 'utf8mb4',
  'engine' => 'InnoDB',
), \Typecho\Db::READ | \Typecho\Db::WRITE);
\Typecho\Db::set();
