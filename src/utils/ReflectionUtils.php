<?php

declare(strict_types=1);


namespace fall\core\utils;

use fall\core\lang\enum\ElementType;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
abstract class ReflectionUtils
{
  private function __construct()
  { }

  public static function setFieldValue($object, string $fieldName, $value): void
  {
    (\Closure::bind(function () use ($fieldName, $value) {
      $this->$fieldName = $value;
    }, $object, get_class($object)))();
  }

  public static function getFieldValue($object, string $fieldName)
  {
    return (\Closure::bind(function () use ($fieldName) {
      return $this->$fieldName;
    }, $object, get_class($object)))();
  }

  public static function setReflectorFieldValue(\Reflector $reflector, object $object, $value): void
  {
    ReflectionUtils::setFieldValue($object, $reflector->getName(), $value);
  }

  public static function getReflectorFieldValue(\Reflector $reflector, $object)
  {
    return ReflectionUtils::getFieldValue($object, $reflector->getName());
  }

  public static function newInstance(string $className): object
  {
    return (new \ReflectionClass($className))->newInstance();
  }

  public static function getReflectorClassName(\Reflector $reflector)
  {
    if ($reflector instanceof \ReflectionClass) {
      return $reflector->getName();
    }

    if ($reflector instanceof \ReflectionMethod || $reflector instanceof \ReflectionProperty) {
      return $reflector->getDeclaringClass()->getName();
    }

    return null;
  }

  public static function getReflectorClassShortName(\Reflector $reflector)
  {
    if ($reflector instanceof \ReflectionClass) {
      return $reflector->getShortName();
    }

    if ($reflector instanceof \ReflectionMethod || $reflector instanceof \ReflectionProperty) {
      return $reflector->getDeclaringClass()->getShortName();
    }

    return null;
  }

  public static function getReflectorFileName(\Reflector $reflector)
  {
    if ($reflector instanceof \ReflectionClass || $reflector instanceof \ReflectionMethod) {
      return $reflector->getFileName();
    }

    if ($reflector instanceof \ReflectionProperty) {
      return $reflector->getDeclaringClass()->getFileName();
    }

    return null;
  }

  public static function getReflectorNamespaceName(\Reflector $reflector)
  {
    if ($reflector instanceof \ReflectionClass || $reflector instanceof \ReflectionMethod) {
      return $reflector->getNamespaceName();
    }

    if ($reflector instanceof \ReflectionProperty) {
      return $reflector->getDeclaringClass()->getNamespaceName();
    }

    return null;
  }

  /**
   * @param $type int (ElementType::TYPE | ElementType::METHOD | ElementType::PROPERTY)
   * @param $className string
   * @param $value string | array - optional
   * @return Reflector[]
   */
  public static function getReflectorsFor(int $type, string $className, $value = null): array
  {
    $reflectionClass = new \ReflectionClass($className);
    $reflectionObjects = [];
    switch ($type) {
      case ElementType::TYPE:
        $reflectionObjects[] = $reflectionClass;
        break;

      case ElementType::METHOD:
        if ($value == null) {
          $reflectionObjects = array_merge($reflectionObjects, $reflectionClass->getMethods());
        } else {
          $reflectionObjects[] = $reflectionClass->getMethod($value);
        }
        break;

      case ElementType::PROPERTY:
        if ($value == null) {
          $reflectionObjects = array_merge($reflectionObjects, $reflectionClass->getProperties());
        } else {
          $reflectionObjects[] = $reflectionClass->getProperty($value);
        }
        break;

      case ElementType::PARAMETER:
        if (\is_array($value)) {
          foreach ($reflectionClass->getMethod($value[0])->getParameters() as $reflectionParameter) {
            if ($reflectionParameter->name === $value[1]) {
              $reflectionObjects[] = $reflectionParameter;
            }
          }
        } else if (\is_string($value)) {
          foreach ($reflectionClass->getMethod($value)->getParameters() as $reflectionParameter) {
            $reflectionObjects[] = $reflectionParameter;
          }
        }
        break;
    }
    return $reflectionObjects;
  }
}
