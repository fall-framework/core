<?php
declare(strict_types=1);


namespace fall\core\lang;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class NoSuchFieldError extends \Error
{
  public function __construct($className, $fieldName)
  {
    parent::__construct('Field ' . $fieldName . ' does not exist in class ' . $className);
  }
}
