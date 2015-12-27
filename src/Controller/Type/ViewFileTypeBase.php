<?php

/**
 * @file
 * Contains Drupal\viewfile\Controller\Type\ViewFileTypeBase.
 */

namespace Drupal\viewfile\Controller\Type;

/**
 * This is the base class for other view types.
 */
class ViewFileTypeBase {

  /**
   * The filename to generate the view.
   *
   * @var string
   */
  protected $filename;

  /**
   * Set which file this view will show.
   *
   * @param string $filename
   *   Set the filename to create the view.
   */
  public function setFilename($filename) {
    $this->filename = $filename;
  }

}
