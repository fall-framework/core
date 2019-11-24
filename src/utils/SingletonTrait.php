<?php
declare(strict_types=1);


namespace fall\core\utils;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
trait SingletonTrait
{
  private static $instance;

  public static function getInstance()
  {
    if (self::$instance === null) {
      self::$instance = new self();
    }
    return self::$instance;
  }
}
