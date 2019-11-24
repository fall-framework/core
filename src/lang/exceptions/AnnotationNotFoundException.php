<?php
declare(strict_types=1);


namespace fall\core\lang\exceptions;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class AnnotationNotFoundException extends \Exception
{
  public function __construct($annotationClassName, $classFileName)
  {
    parent::__construct('Annotation interface not found : "' . $annotationClassName . '" in file ' . $classFileName);
  }
}
