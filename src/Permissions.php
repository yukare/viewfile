<?php

namespace Drupal\viewfile;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides dynamic permissions for folder of different types.
 */
class Permissions {

  use StringTranslationTrait;

  /**
   * Returns an array of folder permissions.
   *
   * @return array
   *   The node type permissions.
   *
   * @see \Drupal\user\PermissionHandlerInterface::getPermissions()
   */
  public function getPermissions() {
    $permissions = [];

    $folders = \Drupal::entityTypeManager()->getStorage('folder')->loadMultiple();

    foreach ($folders as $folder) {
      $permissions += [
        "dynamic permission " . $folder->getLabel() => [
          'title' => $this->t('Sample dynamic @number', ['@number' => $folder->getLabel()]),
          'description' => $this->t('This is a sample permission generated dynamically.'),
        ],
      ];
    }

    return $permissions;
  }

}
