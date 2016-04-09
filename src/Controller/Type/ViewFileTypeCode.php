<?php

/**
 * @file
 * Contains Drupal\viewfile\Controller\Type\ViewFileTypeCode.
 */

namespace Drupal\viewfile\Controller\Type;

use Drupal\geshifilter\GeshiFilterProcess;

/**
 * Show the file with sintax highligthing.
 */
class ViewFileTypeCode extends ViewFileTypeBase implements ViewFileTypeInterface {

  /**
   * Create a render array with the content of file.
   *
   * @return array
   *   Return a render array with the content of file.
   */
  public function content() {
    $attr = array(
      'linenumbers' => '1',
      'title' => '',
      'fancy' => 0,
      'language' => $this->getLanguage(),
    );
    $file = file_get_contents($this->filename);
    $content = GeshiFilterProcess::geshiProcess($file, $attr['language']);

    // Drupal black magic: pass the raw content from geshi to render array.
    $raw = array(
      '#type' => 'inline_template',
      '#template' => '{{ foo|raw }}',
      '#context' => ['foo' => $content],
    );
    return $raw;
  }

  /**
   * Get the language for the file based on extension.
   *
   * @return string
   *   The string with language as used by geshi.
   */
  protected function getLanguage() {
    $language = array(
      'css' => 'css',
      'install' => 'php',
      'module' => 'php',
      'php' => 'php',
      'yml' => 'yaml',
    );
    $extension = pathinfo($this->filename, PATHINFO_EXTENSION);
    return $language[$extension];
  }

}
