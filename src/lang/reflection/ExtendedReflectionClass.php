<?php

declare(strict_types=1);

namespace fall\core\lang\reflection;

use fall\core\lang\Annotation;
use fall\core\lang\enum\ElementType;
use fall\core\utils\AnnotationUtils;

/**
 * Base class representing a reflection class with annotation capabilities
 * 
 * @author Angelis <angelis@users.noreply.github.com>
 */
class ExtendedReflectionClass extends \ReflectionClass implements ExtendedReflector
{
  use ExtendedReflectionObjectTrait;

  public function getMethod($name): ExtendedReflectionMethod
  {
    $method = parent::getMethod($name);
    return new ExtendedReflectionMethod($this->getName(), $method->getName());
  }

  /**
   * @return ExtendedReflectionMethod[]
   */
  public function getMethods($filter = null): array
  {
    $extendedMethods = [];
    $methods = parent::getMethods();
    foreach ($methods as $method) {
      $extendedMethods[] = new ExtendedReflectionMethod($this->getName(), $method->getName());
    }

    return $extendedMethods;
  }

  public function getMethodsAnnotatedWith(string $annotationClass): array
  {
    $extendedMethodsAnnotatedWith = [];
    $extendedMethods = $this->getMethods();
    foreach ($extendedMethods as $extendedMethod) {
      if ($extendedMethod->isAnnotationPresent($annotationClass)) {
        $extendedMethodsAnnotatedWith[] = $extendedMethod;
      }
    }
    return $extendedMethodsAnnotatedWith;
  }

  public function getProperty($name)
  {
    $property = parent::getProperty($name);
    return new ExtendedReflectionProperty($this->getName(), $property->getName());
  }

  public function getProperties($filter = null): array
  {
    if ($filter == null) {
      $filter = \ReflectionProperty::IS_STATIC | \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE;
    }

    $extendedProperties = [];
    $properties = parent::getProperties($filter);
    foreach ($properties as $property) {
      $extendedProperties[] = new ExtendedReflectionProperty($this->getName(), $property->getName());
    }
    return $extendedProperties;
  }

  /**
   * @return ExtendedReflectionProperty[]
   */
  public function getPropertiesAnnotatedWith($annotationClass): array
  {
    $extendedPropertiesAnnotatedWith = [];
    foreach ($this->getProperties() as $extendedProperty) {
      if ($extendedProperty->isAnnotationPresent($annotationClass)) {
        $extendedPropertiesAnnotatedWith[] = $extendedProperty;
      }
    }
    return $extendedPropertiesAnnotatedWith;
  }

  /**
   * @return bool
   */
  public function isUsingTrait(string $traitClass): bool
  {
    return \in_array($traitClass, $this->getTraitNames());
  }

  /**
   * @return bool
   */
  public function isAnnotation(): bool
  {
    return $this->implementsInterface(Annotation::class);
  }

  /**
   * @return AnnotationMetadata[]
   */
  protected function _getAnnotationsMetadata(): array
  {
    return AnnotationUtils::getAnnotationsMetadataFor(ElementType::TYPE, $this->name);
  }
}
