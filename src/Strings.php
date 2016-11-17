<?php

namespace Drupal\viewfile;

/**
 * Static functions to use with strings.
 */
class Strings {

  /**
   * Determine if a given string starts with a given substring.
   *
   * @param string $haystack
   *   The string that we will search into it.
   * @param string|array $needles
   *   One string or an array to search into $haystack.
   *
   * @return bool
   *   TRUE if the string start with the substring.
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
