<?php

namespace Drupal\viewfile\Controller\Type;

/**
 * Interface for types of views.
 */
interface ViewFileTypeInterface {

  /**
   * Set the file name.
   *
   * @param string $filename
   *   Set the filename to create the view.
   */
  public function setFilename($filename);

  /**
   * Create a render array with the content of file.
   *
   * @return array
   *   Return a render array with the content of file.
   */
  public function content();

}
