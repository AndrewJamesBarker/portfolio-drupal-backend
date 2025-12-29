<?php

namespace Drupal\portfolio_skills_migrate\Plugin\migrate\process;

use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Drupal\file\Entity\File;

/**
 * Creates or gets file entity from URI.
 *
 * @MigrateProcessPlugin(
 *   id = "file_uri_to_entity"
 * )
 */
class FileUriToEntity extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property) {
    if (empty($value)) {
      return NULL;
    }

    // Check if file entity already exists.
    $files = \Drupal::entityTypeManager()
      ->getStorage('file')
      ->loadByProperties(['uri' => $value]);

    if ($file = reset($files)) {
      return $file->id();
    }

    // Create new file entity.
    $file = File::create([
      'uri' => $value,
      'status' => 1,
      'uid' => 1,
    ]);
    $file->save();

    return $file->id();
  }

}
