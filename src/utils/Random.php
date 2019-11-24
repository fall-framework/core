<?php
declare(strict_types=1);


namespace fall\core\utils;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
abstract class Random
{

  private function __construct()
  { }

  public static function generateRandomAlpha(int $length = 10): string
  {
    $characters = 'abcdefghijklmnopqrstuvwxyz';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
  }
}
