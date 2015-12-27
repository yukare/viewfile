<?php
/**
 * @file
 * Contains Drupal\viewfile\Controller\Type\ViewFileTypeDownload.
 */

namespace Drupal\viewfile\Controller\Type;

/**
 * Show a file to download.
 */
class ViewFileTypeDownload extends ViewFileTypeBase implements ViewFileTypeInterface {

  /**
   * Create a render array with the content of file.
   *
   * @return array
   *   Return a render array with the content of file.
   */
  public function content() {
    $content = array();
    return $content;
  }

}
