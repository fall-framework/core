<?php

declare(strict_types=1);

namespace fall\core\type;

use fall\core\lang\reflection\ExtendedReflectionClass;
use fall\core\lang\reflection\ExtendedReflectionMethod;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
interface AnnotationMetadata
{
  /**
   * @return ExtendedReflectionMethod[]
   */
  function getAnnotatedMethods(string $annotationName): array;

  /**
   * @return bool
   */
  function hasAnnotation(string $annotationName): bool;
}
