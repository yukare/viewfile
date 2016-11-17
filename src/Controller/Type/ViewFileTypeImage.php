<?php

namespace Drupal\viewfile\Controller\Type;

/**
 * Show the image.
 */
class ViewFileTypeImage extends ViewFileTypeBase implements ViewFileTypeInterface {

  /**
   * Create a render with img tag to show the file.
   *
   * @return array
   *   Return a render array with the img tag to show the file.
   */
  public function content() {
    $content = array();
    return $content;
  }

}
