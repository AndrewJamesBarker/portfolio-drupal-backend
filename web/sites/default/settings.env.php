<?php
$autoload = dirname(__DIR__, 2) . '/vendor/autoload.php';
if (file_exists($autoload)) {
  require_once $autoload;
  if (class_exists(\Dotenv\Dotenv::class)) {
    $dotenv = Dotenv\Dotenv::createImmutable('/var/www/portfolio-backend-drupal/shared');
    $dotenv->safeLoad();
  }
}

$databases['default']['default'] = [
  'database' => getenv('DB_NAME') ?: 'drupal',
  'username' => getenv('DB_USER') ?: 'drupal',
  'password' => getenv('DB_PASS') ?: '',
  'host'     => getenv('DB_HOST') ?: '127.0.0.1',
  'port'     => getenv('DB_PORT') ?: '3306',
  'driver'   => 'mysql',
  'prefix'   => '',
  'collation'=> 'utf8mb4_general_ci',
];

$settings['file_public_path']  = 'sites/default/files';
$settings['file_private_path'] = '/var/www/portfolio-backend-drupal/shared/private';
$settings['hash_salt'] = getenv('HASH_SALT') ?: 'CHANGE_ME';
