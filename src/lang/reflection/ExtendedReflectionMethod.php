<?php

declare(strict_types=1);

namespace fall\core\lang\reflection;

use fall\core\lang\enum\ElementType;
use fall\core\utils\AnnotationUtils;
use fall\core\type\AnnotationMetadata;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class ExtendedReflectionMethod extends \ReflectionMethod implements ExtendedReflector
{
  use ExtendedReflectionObjectTrait;

  public function __construct(string $class, string $name)
  {
    parent::__construct($class, $name);
  }

  public function getDeclaringClass(): ExtendedReflectionClass
  {
    return new ExtendedReflectionClass(parent::getDeclaringClass()->getName());
  }

  /**
   * @return AnnotationMetadata[]
   */
  protected function _getAnnotationsMetadata(): array
  {
    return AnnotationUtils::getAnnotationsMetadataFor(ElementType::METHOD, $this->class, $this->name);
  }
}
