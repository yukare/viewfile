<?php

namespace Drupal\viewfile\Controller;

use Drupal\viewfile\FileTree;
use Drupal\viewfile\Entity\Folder;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class ViewFileController.
 *
 * @package Drupal\viewfile\Controller
 */
class ViewFileController extends ControllerBase {

  /**
   * Return the content for the page.
   *
   * @param Symfony\Component\HttpFoundation\Request $request
   *   The request object from Synfony. From this object, we can get the
   *   file name from query.
   * @param string $folder
   *   The name of folder(1st argument from url).
   *
   * @return array
   *   Return the render array with the content of the page.
   */
  public function content(Request $request, $folder = NULL) {
    $content = array();

    // $file is the name of the file from url.
    $file = $request->query->get('file');

    /* @var Drupal\viewfile\Entity\Folder */
    $entity = entity_load('folder', $folder);

    if ($entity) {
      // $path is the full path to file.
      $dirname = $entity->getAbsolutePath();
      $path = realpath($dirname . '/' . $file);

      $valid = $this->validFile($entity, $path);
      if ($valid) {
        $content[] = $this->renderTree($entity);
        if (is_dir($path)) {
          $content[] = $this->renderDirectory($entity, $path);
        }
        else {
          $content[] = $this->renderFile($entity, $path);
        }
      }
      else {
        // The file is not valid, return a page not found exception.
        throw new NotFoundHttpException();
      }
    }
    else {
      // The entity name is not valid, return a page not found exception.
      throw new NotFoundHttpException();
    }
    return $content;
  }

  /**
   * Render the tree with files/folders.
   *
   * Create and show the tree for a folder.
   *
   * @param \Drupal\viewfile\Entity\Folder $entity
   *   The Folder entity.
   *
   * @return array
   *   One element from a render array with all markup to show
   *   the tree of files.
   */
  protected function renderTree(Folder $entity) {
    $content = array();
    $path = $entity->getAbsolutePath();
    $filetree = new FileTree();
    $params = array(
      'multi' => TRUE,
      'controls' => TRUE,
      'folder' => $entity->id()

      ,
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
   * @param \Drupal\viewfile\Entity\Folder $entity
   *   The folder entity.
   * @param string $path
   *   The name and the full path to file.
   *
   * @return array
   *   One element from a render array to show the content of
   *   the file.
   *
   * @todo Decide how to handle file types that we do not know.
   */
  protected function renderFile(Folder $entity, $path) {
    $types = $this->getTypes();
    $dirname = $entity->getAbsolutePath();

    // Test if the file exists and is inside the folder path.
    if ((strpos($path, $dirname) === 0) && file_exists($path)) {
      $fileinfo = pathinfo($path);
      // Test if we have a controller for this extension.
      if (isset($types[$fileinfo['extension']])) {
        $classname = '\Drupal\viewfile\Controller\Type\ViewFileType' . $types[$fileinfo['extension']];
        /* @var \Drupal\viewfile\Controller\Type\ViewFileTypeInterface $class */
        $class = new $classname();
        $class->setFilename($path);
        $content = $class->content();
      }
      else {
        // We do not have a controller for this extension.
        $classname = '\Drupal\viewfile\Controller\Type\ViewFileTypeCode';
        /* @var \Drupal\viewfile\Controller\Type\ViewFileTypeInterface $class */
        $class = new $classname();
        $class->setFilename($path);
        $content = $class->content();
      }
    }
    return $content;
  }

  /**
   * Render the content of a directory.
   *
   * @param \Drupal\viewfile\Entity\Folder $entity
   *   The folder entity.
   * @param string $path
   *   The name and the full path to file.
   *
   * @return array
   *   One element from a render array to show the content of
   *   the directory.
   */
  protected function renderDirectory(Folder $entity, $path) {
    $content = array();
    return $content;
  }

  /**
   * Test if the file in url is valid.
   *
   * Test if the file exists, and it is inside the given project, so we
   * prevent the opening of a file outside the project.
   *
   * @param object $entity
   *   The folder entity.
   * @param string $path
   *   Absolute path to file.
   *
   * @return bool
   *   TRUE if the file existir and is inside the folder.
   */
  public function validFile($entity, $path) {
    $dirname = $entity->getAbsolutePath();

    // Test if the file exists and is inside the folder path.
    if ((strpos($path, $dirname) === 0) && file_exists($path)) {
      return TRUE;
    }
    // If the file is not valid before, it is invalid.
    return FALSE;
  }

  /**
   * Return the available types of contents.
   *
   * @return array
   *   Return an associative array with the types of content, where the key is
   *   the file extension and the value is the path of the class name.
   */
  protected function getTypes() {
    // Types we know how to handle.
    $types = array(
      'css' => 'Code',
      'inc' => 'Code',
      'jpg' => 'Image',
      'json' => 'Code',
      'md' => 'Code',
      'module' => 'Code',
      'php' => 'Code',
      'png' => 'Image',
      'yml' => 'Code',
    );
    return $types;
  }

}
