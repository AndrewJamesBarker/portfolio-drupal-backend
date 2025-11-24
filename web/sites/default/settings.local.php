<?php

/**
 * @file
 * Local development override configuration.
 *
 * This file is automatically loaded in DDEV environments.
 */

// Only apply these settings in DDEV environment
if (getenv('DDEV_PROJECT')) {
  
  // Disable render cache
  $settings['cache']['bins']['render'] = 'cache.backend.null';
  $settings['cache']['bins']['page'] = 'cache.backend.null';
  $settings['cache']['bins']['dynamic_page_cache'] = 'cache.backend.null';

  // Load development services (disables Twig cache)
  $settings['container_yamls'][] = DRUPAL_ROOT . '/sites/development.services.yml';

  // Enable Twig debugging
  $settings['twig_debug'] = TRUE;
  $settings['twig_auto_reload'] = TRUE;
  $settings['twig_cache'] = FALSE;

  // Disable CSS and JS aggregation
  $config['system.performance']['css']['preprocess'] = FALSE;
  $config['system.performance']['js']['preprocess'] = FALSE;

  // Enable verbose error logging
  $config['system.logging']['error_level'] = 'verbose';

  // Show all error messages on the site
  error_reporting(E_ALL);
  ini_set('display_errors', TRUE);
  ini_set('display_startup_errors', TRUE);

  // Disable the render cache (this is the most important one)
  $settings['cache']['bins']['render'] = 'cache.backend.null';
}