<?php

/**
 * @file
 * Contains Drupal\viewfile\Entity\Folder.
 */

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
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "path" = "path"
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
   * @var string
   */
  protected $id;

  /**
   * The Folder label.
   *
   * @var string
   */
  protected $name;

  /**
   * The path to folder.
   *
   * @var string
   */
  protected $path;

  /**
   * Get the path to folder.
   *
   * @return string
   *   Return the path to folder.
   */
  public function getPath() {
    return $this->path;
  }

}
