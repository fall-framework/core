<?php
declare(strict_types=1);


namespace fall\core\utils;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
abstract class StringUtils
{
  private function __constuct()
  { }

  public static function translateMicrosoftQuoteToStandard($message)
  {
    $search = [chr(145), chr(146), chr(147), chr(148), chr(151)];
    $replace = ["'", "'", '"', '"', '-'];

    return str_replace($search, $replace, $message);
  }

  public static function startsWith($haystack, $needle)
  {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
  }

  public static function endsWith($haystack, $needle)
  {
    // search forward starting from end minus needle length characters
    return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
  }

  public static function indexOf($string, $search)
  {
    return strpos($string, $search);
  }
}
