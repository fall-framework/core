<?php

declare(strict_types=1);

namespace fall\core\type;

use fall\core\lang\reflection\ExtendedReflectionClass;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class StandardClassMetadata implements ClassMetadata
{
  private $extendedReflectionClass;

  public function __construct(ExtendedReflectionClass $extendedReflectionClass)
  {
    $this->extendedReflectionClass = $extendedReflectionClass;
  }

  /**
   * @return string
   */
  public function getClassName(): string
  {
    return $this->extendedReflectionClass->getName();
  }

  /**
   * @return string[]
   */
  public function getInterfaceNames(): array
  {
    return $this->extendedReflectionClass->getInterfaceNames();
  }

  /**
   * @return string|null
   */
  public function getSuperClassName(): ?string
  {
    if (!$this->hasSuperClass()) {
      return null;
    }

    return $this->extendedReflectionClass->getParentClass()->getName();
  }

  /**
   * @return bool
   */
  public function hasSuperClass(): bool
  {
    return $this->extendedReflectionClass->getParentClass() !== false;
  }

  /**
   * @return bool
   */
  public function isAbstract(): bool
  {
    return $this->extendedReflectionClass->isAbstract();
  }

  /**
   * @return bool
   */
  public function isFinal(): bool
  {
    return $this->extendedReflectionClass->isFinal();
  }

  /**
   * @return bool
   */
  public function isInterface(): bool
  {
    return $this->extendedReflectionClass->isInterface();
  }

  /**
   * @return ExtendedReflectionClass
   */
  public function getExtendedReflectionClass(): ExtendedReflectionClass
  {
    return $this->extendedReflectionClass;
  }
}
