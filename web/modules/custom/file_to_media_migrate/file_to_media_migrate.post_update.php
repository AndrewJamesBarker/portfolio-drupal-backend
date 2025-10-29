<?php
/**
 * Set alt text on migrated files to media to file name
 */
function file_to_media_migrate_post_update_set_image_alt_text(array &$sandbox): void
{
  $storage = \Drupal::entityTypeManager()->getStorage('media');

  $media_ids = $storage->getQuery()
    ->accessCheck(FALSE)
    ->condition('bundle', 'image')
    ->execute();

  foreach ($storage->loadMultiple($media_ids) as $media) {
    $image = $media->get('field_media_image')->first();
    $alt = $image->get('alt')->getValue();
    if ((empty($alt) || $alt === 'Array')) {

      $filename = $media->label();
      $image->set('alt', $filename);
      $media->save();
    }
  }


}
