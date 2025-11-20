<?php

use Dotenv\Dotenv;

// DRUPAL_ROOT is something like:
//   /var/www/portfolio-backend-drupal/releases/0001/web
// Project root is three levels up:
//   /var/www/portfolio-backend-drupal
$projectRoot = dirname(DRUPAL_ROOT, 3);
$envDir = $projectRoot . '/shared';

// Debug breadcrumbs
//error_log('settings.env.php loaded from: ' . __FILE__);
//error_log('settings.env.php: projectRoot = ' . $projectRoot);
//error_log('settings.env.php: envDir = ' . $envDir);

// Composer autoload – via the "current" symlink
$autoload = dirname(DRUPAL_ROOT) . '/vendor/autoload.php';
if (file_exists($autoload)) {
  require_once $autoload;
} else {
  error_log('settings.env.php: autoload not found at ' . $autoload);
}

// Load .env if present
if (file_exists($envDir . '/.env')) {
  $dotenv = Dotenv::createImmutable($envDir);
  $dotenv->load();
} else {
  error_log('settings.env.php: .env NOT FOUND in ' . $envDir);
}

// Merge env sources, prioritizing $_ENV/$_SERVER
$env = array_merge($_SERVER, $_ENV);

$databases['default']['default'] = [
  'database' => $env['DB_NAME'] ?? 'portfolio_backend',
  'username' => $env['DB_USER'] ?? 'portfolio_user',
  'password' => $env['DB_PASS'] ?? '',
  'host'     => $env['DB_HOST'] ?? '127.0.0.1',
  'port'     => $env['DB_PORT'] ?? '3306',
  'driver'   => 'mysql',
  'prefix'   => '',
  'collation'=> 'utf8mb4_general_ci',
];

// File paths and hash salt
$settings['file_public_path']  = 'sites/default/files';
$settings['file_private_path'] = $projectRoot . '/shared/private';
$settings['hash_salt']         = $env['HASH_SALT'] ?? 'CHANGE_ME';

//var_dump($databases);
