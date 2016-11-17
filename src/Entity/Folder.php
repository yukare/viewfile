<?php

namespace Drupal\viewfile\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\viewfile\FolderInterface;

/**
 * Defines the Folder entity.
 *
 * @ConfigEntityType(
 *   id = "folder",
 *   config_prefix = "folder",
 *   label = @Translation("Folder"),
 *   handlers = {
 *     "storage" = "Drupal\Core\Config\Entity\ConfigEntityStorage",
 *     "list_builder" = "Drupal\viewfile\Controller\FolderListBuilder",
 *     "form" = {
 *       "add" = "Drupal\viewfile\Form\FolderForm",
 *       "edit" = "Drupal\viewfile\Form\FolderForm",
 *       "delete" = "Drupal\viewfile\Form\FolderDeleteForm"
 *     }
 *   },
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "edit-form" = "/admin/config/system/folder/{folder}",
 *     "delete-form" = "/admin/config/system/folder/{folder}/delete"
 *   }
 * )
 */
class Folder extends ConfigEntityBase implements FolderInterface {
  /**
   * The Folder ID.
   *
   * The machine name of the folder.
   *
   * @var string
   */
  protected $id;

  /**
   * The Folder label.
   *
   * The user readable name of the folder.
   *
   * @var string
   */
  protected $label;

  /**
   * The path to folder.
   *
   * @var string
   */
  protected $path;

  /**
   * If this folder is private.
   *
   * True if this file will be visible only to users with the permission
   * to view this folder and not with permission to see all folders.
   *
   * @var bool
   */
  protected $private;

  /**
   * If this folder have a permission.
   *
   * True if this folder has its own permission.
   *
   * @var bool
   */
  protected $permission;

  /**
   * Get the label(name) of the folder.
   *
   * @return string
   *   Return the label of the folder.
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * Get the path to folder.
   *
   * @return string
   *   Return the path to folder.
   */
  public function getPath() {
    return $this->path;
  }

  /**
   * Get if this folder has a permission.
   *
   * @return bool
   *   If this folder has permission.
   */
  public function getPermission() {
    return $this->permission;
  }

  /**
   * Get if this folder is private.
   *
   * @return bool
   *   If this folder is private.
   */
  public function getPrivate() {
    return $this->permission;
  }

  /**
   * Get the absolute path to folder.
   *
   * This function convert relative paths to absolute and convert drupal uri
   * like private: and public:.
   *
   * @return string
   *   The absolute path to folder in the filesystem.
   */
  public function getAbsolutePath() {
    $path = $this->getPath();
    // We have a drupal path public:// or private://.
    if ($this->startsWith($path, array('public://', 'private://'))) {
      return drupal_realpath($path);
    }
    // We have an absolute path.
    elseif ($this->startsWith($path, '/')) {
      return realpath($path);
    }
    // We have an relative path.
    return realpath(DRUPAL_ROOT . '/' . $path);
  }

  /**
   * Determine if a given string starts with a given substring.
   *
   * @param string $haystack
   *   The string that we will search into it.
   * @param string|array $needles
   *   One string or an array to search into $haystack.
   *
   * @return bool
   *   TRUE if the string $haystack start with one from $needles.
   */
  public static function startsWith($haystack, $needles) {
    foreach ((array) $needles as $needle) {
      if ($needle != '' && strpos($haystack, $needle) === 0) {
        return TRUE;
      }
    }
    return FALSE;
  }

}
