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

    $realpath = StreamWrapperManager::getTarget($directory);

    if (!is_dir($realpath)) {
      throw new \InvalidArgumentException("Directory not found: {$directory}");
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

      $rows[] = [
        'filename' => $file,
        'uri' => $directory . '/' . $file,
      ];
    }

    return new \ArrayIterator($rows);
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    $filename = $row->getSourceProperty('filename');
    $base = explode('.', $filename)[0];

    $skill_key = strtolower($base);
    $label = ucfirst($base);

    $row->setSourceProperty('skill_key', $skill_key);
    $row->setSourceProperty('label', $label);

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
