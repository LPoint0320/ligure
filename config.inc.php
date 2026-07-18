<?php
define('__TYPECHO_ROOT_DIR__', dirname(__FILE__));
define('__TYPECHO_PLUGIN_DIR__', '/usr/plugins');
define('__TYPECHO_THEME_DIR__', '/usr/themes');
define('__TYPECHO_ADMIN_DIR__', '/admin/');
require_once __TYPECHO_ROOT_DIR__ . '/var/Typecho/Common.php';
\Typecho\Common::init();

// Vercel / Railway / cloud env — support multiple env var formats
$mysqlUrl = getenv('MYSQL_URL') ?: getenv('DATABASE_URL') ?: '';
if ($mysqlUrl) {
    $parts = parse_url($mysqlUrl);
    $host = $parts['host'] ?? '127.0.0.1';
    $port = $parts['port'] ?? 3306;
    $user = $parts['user'] ?? 'root';
    $pass = $parts['pass'] ?? '';
    $dbname = ltrim($parts['path'] ?? '', '/');
} else {
    $host = getenv('MYSQLHOST') ?: getenv('MYSQL_HOST') ?: '127.0.0.1';
    $port = getenv('MYSQLPORT') ?: getenv('MYSQL_PORT') ?: 3306;
    $user = getenv('MYSQLUSER') ?: getenv('MYSQL_USER') ?: 'root';
    $pass = getenv('MYSQLPASSWORD') ?: getenv('MYSQL_PASSWORD') ?: '';
    $dbname = getenv('MYSQLDATABASE') ?: getenv('MYSQL_DATABASE') ?: 'typecho';
}

$db = new \Typecho\Db('Pdo_Mysql', 'typecho_');
$db->addServer(array(
  'host' => $host,
  'port' => intval($port),
  'user' => $user,
  'password' => $pass,
  'database' => $dbname,
  'charset' => 'utf8mb4',
  'engine' => 'InnoDB',
), \Typecho\Db::READ | \Typecho\Db::WRITE);
\Typecho\Db::set($db);
