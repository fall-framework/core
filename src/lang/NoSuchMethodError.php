<?php
declare(strict_types=1);


namespace fall\core\lang;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class NoSuchMethodError extends \Error
{
  public function __construct($className, $methodName)
  {
    parent::__construct('Method ' . $methodName . ' does not exist in class ' . $className);
  }
}
