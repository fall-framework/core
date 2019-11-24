<?php
declare(strict_types=1);


namespace fall\core\lang\exceptions;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class ConstructorNotFoundException extends \Exception
{
  public function __construct($className)
  {
    parent::__construct('Constructor not found for class : ' . $className);
  }
}
