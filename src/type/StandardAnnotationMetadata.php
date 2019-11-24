<?php

declare(strict_types=1);

namespace fall\core\type;

use fall\core\lang\reflection\ExtendedReflectionClass;
use fall\core\lang\reflection\ExtendedReflectionMethod;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class StandardAnnotationMetadata extends StandardClassMetadata implements AnnotationMetadata
{
  private $annotations;

  public function __construct(ExtendedReflectionClass $extendedReflectionClass)
  {
    parent::__construct($extendedReflectionClass);
    $this->annotations = $extendedReflectionClass->getAnnotations();
  }

  /**
   * @return ExtendedReflectionMethod[]
   */
  public function getAnnotatedMethods(string $annotationName): array
  {
    $annotatedMethods = [];
    foreach ($this->getExtendedReflectionClass()->getMethods() as $method) {
      foreach ($method->getAnnotations() as $annotation) {
        if ($annotation->annotationType()->getName() === $annotationName) {
          $annotatedMethods[] = $method;
        }
      }
    }

    return $annotatedMethods;
  }

  public function hasAnnotation(string $annotationName): bool
  {
    foreach ($this->annotations as $annotation) {
      if ($annotation->annotationType()->getName() === $annotationName) {
        return true;
      }
    }

    return false;
  }
}

/*
$proxyExtendedReflectionClass = AnnotationUtils::getAnnotationMetadataProxyForReflectionClass($annotationExtendedReflectionClass);
    $annotationParameters = AnnotationUtils::getAnnotationMetadataParameters($annotationArray[2]);

    $annotationMethodNames = ArrayUtils::map($proxyExtendedReflectionClass->getMethods(), function ($element) {
      return $element->getName();
    });

    foreach ($annotationParameters as $key => $value) {
      if ($key === '_default') {
        if (count($proxyExtendedReflectionClass->getMethods()) === 0) {
          throw new \Exception("Annotation " . ReflectionUtils::getReflectorClassShortName($annotationExtendedReflectionClass) . " don't have any property in file " . ReflectionUtils::getReflectorFileName($reflector));
        }
      } else if (!\in_array($key, $annotationMethodNames)) {
        throw new \Exception("Annotation " . ReflectionUtils::getReflectorClassShortName($annotationExtendedReflectionClass) . " don't have property " . $key . " in file " . ReflectionUtils::getReflectorFileName($reflector));
      }
    }

    return new AnnotationMetadata($annotationExtendedReflectionClass, $proxyExtendedReflectionClass, $reflector, $annotationParameters);
    */


    /*

class AnnotationMetadata
{
  private $annotationExtendedReflectionClass;
  private $proxyExtendedReflectionClass;
  private $parentAnnotationMetadata = [];
  private $targetReflector;
  private $parameters;

  public function __construct(ExtendedReflectionClass $annotationExtendedReflectionClass, ExtendedReflectionClass $proxyExtendedReflectionClass, \Reflector $targetReflector, array $parameters = [])
  {
    $this->annotationExtendedReflectionClass = $annotationExtendedReflectionClass;
    $this->proxyExtendedReflectionClass = $proxyExtendedReflectionClass;
    $this->targetReflector = $targetReflector;
    $this->parameters = $parameters;
  }

  public function getAnnotationExtendedReflectionClass(): \ReflectionClass
  {
    return $this->annotationExtendedReflectionClass;
  }

  public function getProxyReflectionClass(): \ReflectionClass
  {
    return $this->proxyExtendedReflectionClass;
  }

  public function getParentAnnotationMetadata(): array
  {
    return $this->parentAnnotationMetadata;
  }

  public function build(): object
  {
    $proxyInstance = $this->proxyExtendedReflectionClass->newInstance();

    $memberValues = [];
    foreach ($this->annotationExtendedReflectionClass->getMethods() as $method) {
      $value = null;
      if (isset($this->parameters[$method->getName()])) {
        $value = $this->parameters[$method->getName()];
      } else if ($method->isAnnotationPresent(DefaultValue::class)) {
        $value = $method->getAnnotation(DefaultValue::class)->value();
      } else if (isset($this->parameters['_default'])) {
        $value = $this->parameters['_default'];
        unset($this->parameters['_default']);
      } else {
        continue;
      }

      $memberValues[$method->getName()] = $this->parseAnnotationValueExpression($value);
    }

    ReflectionUtils::setFieldValue($proxyInstance, 'memberValues', $memberValues);
    return $proxyInstance;
  }

  private function parseAnnotationValueExpression(string $annotationValueExpression)
  {
    $value = $annotationValueExpression;
    switch (true) {
      case (StringUtils::indexOf($value, '::') > -1):
        $fileUses = PHPUtils::getAllFileUses(ReflectionUtils::getReflectorFileName($this->targetReflector));
        list($className, $property) = explode('::', $value);
        if (!\class_exists($className)) {
          if (!isset($fileUses[$className])) {
            throw new \Exception("Class not found " + $className);
          }
          $className = $fileUses[$className];
        }
        $enumReflectionClass = new \ReflectionClass($className);
        switch (true) {
          case $property === 'class':
            $value = '"' . $enumReflectionClass->getName() . '"';
            break;
          default:
            $value = $enumReflectionClass->getConstant($property);
        }
        break;

      case (StringUtils::startsWith($value, '%')):
        switch (substr($value, 1)) {
          case 'class.name':
            $value = ReflectionUtils::getReflectorClassName($this->annotationExtendedReflectionClass);
            break;

          case 'class.short.name':
            $value = ReflectionUtils::getReflectorClassShortName($this->annotationExtendedReflectionClass);
            break;

          case 'target.class.name':
            $value = ReflectionUtils::getReflectorClassName($this->targetReflector);
            break;

          case 'target.class.short.name':
            $value = ReflectionUtils::getReflectorClassShortName($this->targetReflector);
            break;

          default:
            throw new \Exception("Expression " . $value . " not valid !");
        }
        break;
    }

    if ($value === '') {
      $value = '""';
    }

    return $value;
  }
}


     */
