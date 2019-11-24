<?php
declare(strict_types=1);


namespace fall\core\lang\reflection;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
interface ExtendedReflector extends \Reflector
{
  function getAnnotationsMetadata(): array;
  function isAnnotationPresent(string $annotationClass): bool;
}
