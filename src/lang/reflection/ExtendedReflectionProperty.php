<?php

declare(strict_types=1);

namespace fall\core\lang\reflection;

use fall\core\lang\enum\ElementType;
use fall\core\utils\AnnotationUtils;
use fall\core\type\AnnotationMetadata;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class ExtendedReflectionProperty extends \ReflectionProperty implements ExtendedReflector
{
  use ExtendedReflectionObjectTrait;

  public function __construct(string $class, string $name)
  {
    parent::__construct($class, $name);
  }

  /**
   * @return bool
   */
  public function isAccessible(): bool
  {
    return !$this->isPrivate() && !$this->isProtected();
  }

  /**
   * @return string
   */
  public function getNamespaceName(): string
  {
    return $this->getDeclaringClass()->getNamespaceName();
  }

  /**
   * @return AnnotationMetadata[]
   */
  protected function _getAnnotationsMetadata(): array
  {
    return AnnotationUtils::getAnnotationsMetadataFor(ElementType::PROPERTY, $this->class, $this->name);
  }
}
