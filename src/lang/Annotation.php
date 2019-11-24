<?php

declare(strict_types=1);

namespace fall\core\lang;

use fall\core\lang\reflection\ExtendedReflectionClass;

/**
 * Base interface for all @see Annotation
 * @author Angelis <angelis@users.noreply.github.com>
 */
interface Annotation
{
  /**
   * Return the @see ExtentedReflectionClass corresponding to this annotation
   * @return ExtendedReflectionClass
   */
  function annotationType(): ExtendedReflectionClass;
}
