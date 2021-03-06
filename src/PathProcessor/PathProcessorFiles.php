<?php

namespace Drupal\viewfile\PathProcessor;

use Drupal\Core\PathProcessor\InboundPathProcessorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Defines a path processor to rewrite file URLs.
 *
 * As the route system does not allow arbitrary amount of parameters convert
 * the file path to a query parameter on the request.
 */
class PathProcessorFiles implements InboundPathProcessorInterface {

  /**
   * {@inheritdoc}
   */
  public function processInbound($path, Request $request) {
    if (strpos($path, '/view/') === 0) {
      $pieces = explode('/', $path);
      $folder = $pieces[2];
      unset($pieces[2]);
      unset($pieces[1]);
      unset($pieces[0]);
      $path = implode('/', $pieces);
      $request->query->set('file', $path);
      return '/view/' . $folder;
    }
    return $path;
  }

}
