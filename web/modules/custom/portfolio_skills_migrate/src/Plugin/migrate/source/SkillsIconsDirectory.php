<?php

namespace Drupal\portfolio_skills_migrate\Plugin\migrate\source;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Row;
use Drupal\Core\StreamWrapper\StreamWrapperManager;

/**
 * Source plugin for reading skill icon files from a directory.
 *
 * @MigrateSource(
 *   id = "skills_icons_directory"
 * )
 */
class SkillsIconsDirectory extends SourcePluginBase {

  /**
   * {@inheritdoc}
   */
  public function __toString() {
    return 'skills_icons_directory';
  }

  /**
   * {@inheritdoc}
   */
  public function initializeIterator() {
    $directory = $this->configuration['directory'];
    $extensions = $this->configuration['file_extensions'] ?? [];

    // Get the real filesystem path.
    /** @var \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface $stream_wrapper_manager */
    $stream_wrapper_manager = \Drupal::service('stream_wrapper_manager');
    $stream_wrapper = $stream_wrapper_manager->getViaUri($directory);
    
    if (!$stream_wrapper) {
      throw new \InvalidArgumentException("Invalid stream wrapper URI: {$directory}");
    }
    
    $realpath = $stream_wrapper->realpath();

    if (!is_dir($realpath)) {
      throw new \InvalidArgumentException("Directory not found: {$directory} (resolved to: {$realpath})");
    }

    $rows = [];

    foreach (scandir($realpath) as $file) {
      if ($file === '.' || $file === '..') {
        continue;
      }

      $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
      if (!empty($extensions) && !in_array($ext, $extensions)) {
        continue;
      }

      // Derive skill_key and label from filename.
      $base = explode('.', $file)[0];
      $skill_key = strtolower($base);
      $label = ucfirst($base);

      $rows[] = [
        'filename' => $file,
        'uri' => $directory . '/' . $file,
        'skill_key' => $skill_key,
        'label' => $label,
      ];
    }

    return new \ArrayIterator($rows);
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    // All processing is done in initializeIterator() since skill_key is the ID.
    return parent::prepareRow($row);
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    return [
      'filename' => $this->t('Filename'),
      'uri' => $this->t('File URI'),
      'skill_key' => $this->t('Skill machine key'),
      'label' => $this->t('Skill label'),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getIds() {
    return [
      'skill_key' => [
        'type' => 'string',
        'alias' => 'f',
      ],
    ];
  }

}
