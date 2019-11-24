<?php
declare(strict_types=1);


namespace fall\core\lang\exceptions;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class AnnotationBadConfigurationException extends \Exception
{
  public function __construct($annotationInterfaceName, $parameterName)
  {
    parent::__construct('Annotation interface "' . $annotationInterfaceName . '" need to extends Annotation interface in file ' . $parameterName);
  }
}
