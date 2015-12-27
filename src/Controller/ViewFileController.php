<?php

/**
 * @file
 * Contains Drupal\viewfile\Controller\ViewFileControllerer.
 */

namespace Drupal\viewfile\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ViewFileController.
 *
 * @package Drupal\viewfile\Controller
 */
class ViewFileController extends ControllerBase {
  /**
   * Return the content for the page.
   *
   * @param string $folder
   *   The name of folder(1st argument from url).
   * @param string $file
   *   The name of the file(all the rest of url).
   *
   * @return array
   *   Return the render array with the content of the page.
   */
  public function content($folder = NULL, Request $request) {
    $file = $request->query->get('file');
    $content = array();
    $entity = entity_load('folder', $folder);
    if ($entity) {
      $content[] = $this->renderTree($folder);
      $content[] = $this->renderFile($folder, $file);
    }
    return $content;
  }

  /**
   * Render the tree with files/folders.
   *
   * Create and show the tree for a folder.
   *
   * @param string $folder
   *   The folder name.
   *
   * @return array
   *   One element from a render array with all markup to show
   *   the tree of files.
   */
  protected function renderTree($folder) {
    $content = array();
    $entity = entity_load('folder', $folder);
    $path = $this->getAbsolutePath($entity->getPath());
    $filetree = new \Drupal\viewfile\FileTree();
    $params = array(
      'multi' => TRUE,
      'controls' => TRUE,
      'folder' => $folder,
      'folderRoot' => $path,
      'animation' => FALSE,
    );
    $files = $filetree->listFiles($path, $path, $params);
    $content['#markup'] = $filetree->render($files, $params);
    $content['#attached']['library'][] = 'viewfile/filetree';
    return ($content);
  }

  /**
   * Render the content of a file.
   *
   * This function create the html markup to show the file depending on file
   * type. A class is used for the real work for each file type.
   *
   * @param string $folder
   *   The name of the folder.
   * @param string $file
   *   The name and the path of file inside folder.
   *
   * @return array
   *   One element from a render array to show the content of
   *   the file.
   *
   * @todo Decide how to handle file types that we do not know.
   */
  protected function renderFile($folder, $file) {
    $types = $this->getTypes();

    $entity = entity_load('folder', $folder);

    $dirname = $this->getAbsolutePath($entity->getPath());
    $filename = $dirname . '/' . $file;
    // Test if the file exists and is inside the folder path.
    if ((strpos($filename, $dirname) === 0) && file_exists($filename)) {
      $fileinfo = pathinfo($filename);
      // Test if we have a controller for this extension.
      if (isset($types[$fileinfo['extension']])) {
        $classname = '\Drupal\viewfile\Controller\Type\ViewFileType' . $types[$fileinfo['extension']];
        /** @var \Drupal\viewfile\Controller\Type\ViewFileTypeInterface $class */
        $class = new $classname();
        $class->setFilename($filename);
        $content = $class->content();
      }
      else {
        // We do not have a controller for this extension.
        $classname = '\Drupal\viewfile\Controller\Type\ViewFileTypeCode';
        /** @var \Drupal\viewfile\Controller\Type\ViewFileTypeInterface $class */
        $class = new $classname();
        $class->setFilename($filename);
        $content = $class->content();
      }
    }
    return $content;
  }

  public function getAbsolutePath($path) {
    // We have a drupal path public:// or private://.
    if($this->startsWith($path,array('public://', 'private://'))) {
      return drupal_realpath($path);
    }
    // We have an absolute path.
    elseif($this->startsWith($path, '/')) {
      return $path;
    }
    // We have an relative path.
    return realpath(DRUPAL_ROOT . '/' . $path);
  }

  /**
   * Determine if a given string starts with a given substring.
   *
   * @param  string  $haystack
   * @param  string|array  $needles
   * @return bool
   */
  public static function startsWith($haystack, $needles)
  {
    foreach ((array) $needles as $needle)
    {
      if ($needle != '' && strpos($haystack, $needle) === 0) return true;
    }
    return false;
  }

  protected function getTypes() {
    // Types we know how to handle.
    $types = array(
      'php' => 'Code',
      'jpeg' => 'Image',
      'png' => 'Image',
      'yml' => 'Code',
    );
    return $types;
  }
}

