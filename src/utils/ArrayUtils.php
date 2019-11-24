<?php
declare(strict_types=1);


namespace fall\core\utils;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
abstract class ArrayUtils
{
  private function __constuct()
  { }

  public static function buildSubKeyFromString($string, $separator = '.')
  {
    $paths = explode($separator, $string);
    $arrayKeyString = '';
    foreach ($paths as $path) {
      $arrayKeyString .= '["' . $path . '"]';
    }

    return $arrayKeyString;
  }

  public static function getSubKeyValueFromString(&$array, $string, $separator = '.')
  {
    $temp = &$array;
    foreach (explode($separator, $string) as $key) {
      if (isset($temp[$key])) {
        $temp = &$temp[$key];
      }
    }
    return $temp;
  }

  public static function setSubKeyValueFromString(&$array, $string, $value, $separator = '.')
  {
    $temp = &$array;
    foreach (explode($separator, $string) as $key) {
      $temp = &$temp[$key];
    }
    $temp = $value;
  }

  public static function isArrayOfString(array $array)
  {
    foreach ($array as $element) {
      if (!\is_string($element)) {
        return false;
      }
    }

    return true;
  }

  public static function map(array $array, \Closure $callback): array
  {
    return array_map($callback, $array);
  }

  public static function append(array &$array, $value)
  {
    if (\is_array($value)) {
      foreach ($value as $v) {
        ArrayUtils::append($array, $v);
      }
    } else {
      $array[] = $value;
    }
  }
}
