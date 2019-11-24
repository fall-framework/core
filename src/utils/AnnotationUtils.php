<?php

declare(strict_types=1);


namespace fall\core\utils;

use fall\core\lang\Annotation;
use fall\core\lang\enum\ElementType;
use fall\core\lang\exceptions\AnnotationBadConfigurationException;
use fall\core\lang\exceptions\AnnotationNotFoundException;
use fall\core\lang\reflection\ExtendedReflectionClass;
use fall\core\type\StandardAnnotationMetadata;
use fall\core\type\AnnotationMetadata;
use fall\core\utils\ClassUtils;
use fall\core\utils\Stream;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
abstract class AnnotationUtils
{

  private static $count = 0;
  private static $annotationCache = [];

  private function __construct()
  { }

  public static function getAllExtendedReflectionClassesHavingAnnotation(string $annotationClass): array
  {
    return AnnotationUtils::getAllExtendedReflectionObjectHavingAnnotation($annotationClass, get_declared_classes());
  }

  public static function getAllExtendedReflectionTraitHavingAnnotation(string $annotationClass): array
  {
    return AnnotationUtils::getAllExtendedReflectionObjectHavingAnnotation($annotationClass, get_declared_traits());
  }

  public static function getAllExtendedReflectionObjectHavingAnnotation(string $annotationClass, array $objectNames = []): array
  {
    $objectsHavingAnnotations = [];
    foreach ($objectNames as $objectName) {
      $extendedReflectionClass = new ExtendedReflectionClass($objectName);
      if ($extendedReflectionClass->isInternal()) {
        continue;
      }

      if ($extendedReflectionClass->isAnnotationPresent($annotationClass)) {
        $objectsHavingAnnotations[] = $extendedReflectionClass;
      }
    }

    return $objectsHavingAnnotations;
  }

  public static function getAllExtendedReflectionClassesUsingTrait(string $traitClass)
  {
    $objectsHavingAnnotations = [];
    foreach (get_declared_classes() as $className) {
      $extendedReflectionClass = new ExtendedReflectionClass($className);
      if ($extendedReflectionClass->isInternal()) {
        continue;
      }

      if ($extendedReflectionClass->isUsingTrait($traitClass)) {
        $objectsHavingAnnotations[] = $extendedReflectionClass;
      }
    }

    return $objectsHavingAnnotations;
  }

  /**
   * @return AnnotationMetadata[]
   */
  public static function getAnnotationsMetadataFor(int $type, string $className, $value = null): array
  {
    $reflectors = ReflectionUtils::getReflectorsFor($type, $className, $value);

    $referenceReflectionClass = new \ReflectionClass($className);
    if ($referenceReflectionClass->getFileName() === false) {
      return [];
    }

    return AnnotationUtils::getAnnotationsMetadataForReflectors($reflectors);
  }

  /**
   * @return AnnotationMetadata[]
   */
  public static function getAnnotationsMetadataForReflectors(array $reflectors): array
  {
    $annotationsMetadata = [];
    foreach ($reflectors as $reflector) {
      ArrayUtils::append($annotationsMetadata, AnnotationUtils::getAnnotationsMetadataForReflector($reflector));
    }

    return $annotationsMetadata;
  }

  public static function getAnnotationsMetadataForReflector(\Reflector $reflector): array
  {
    $docComment = $reflector->getDocComment();
    if (!$docComment || !\preg_match_all('#\@([A-Z]{1}.+)#m', $docComment, $annotationDocComments, PREG_SET_ORDER)) {
      return [];
    }

    $annotationsMetadata = [];
    foreach ($annotationDocComments as $annotationDocComment) {
      ArrayUtils::append($annotationsMetadata, AnnotationUtils::parseAnnotationMetadataForReflectorAndString($reflector, $annotationDocComment[1]));
    }

    foreach ($annotationsMetadata as $annotationMetadata) {
      $interfacesReflectionClasses = $annotationMetadata->getProxyReflectionClass()->getInterfaces();
      $parentAnnotationMetadata = [];
      foreach ($interfacesReflectionClasses as $interfacesReflectionClass) {
        if ($interfacesReflectionClass->getName() === Annotation::class) {
          continue;
        }

        ArrayUtils::append($parentAnnotationMetadata, AnnotationUtils::getAnnotationsMetadataFor(ElementType::TYPE, $interfacesReflectionClass->getName()));
      }

      ReflectionUtils::setFieldValue($annotationMetadata, 'parentAnnotationMetadata', $parentAnnotationMetadata);

      Stream::of($parentAnnotationMetadata)
        ->recursiveMap(function (AnnotationMetadata $element) {
          return $element->getParentAnnotationMetadata();
        })
        ->each(function (&$element) use ($reflector) {
          ReflectionUtils::setFieldValue($element, 'targetReflector', $reflector);
        });
    }

    return $annotationsMetadata;
  }

  private static function parseAnnotationMetadataForReflectorAndString(\Reflector $reflector, string $annotationString): AnnotationMetadata
  {
    if (!preg_match('#([a-zA-Z0-9]+)\(([a-zA-Z0-9 \%\.\:\/"\'\=\{\}]*)\)#', $annotationString, $annotationArray)) {
      throw new \Exception('Annotation mal formatted in ' . ReflectionUtils::getReflectorFileName($reflector));
    }

    $annotationInterfaceName = trim($annotationArray[1]);

    if (!interface_exists($annotationInterfaceName)) {
      $fileUses = PHPUtils::getAllFileUses(ReflectionUtils::getReflectorFileName($reflector));
      if (isset($fileUses[$annotationInterfaceName])) {
        $annotationInterfaceName = $fileUses[$annotationInterfaceName];
      } else {
        $reflectionObjectNamespace = ReflectionUtils::getReflectorNamespaceName($reflector);
        if (interface_exists($reflectionObjectNamespace . "\\" . $annotationInterfaceName)) {
          $annotationInterfaceName = $reflectionObjectNamespace . "\\" . $annotationInterfaceName;
        }
      }

      if (!interface_exists($annotationInterfaceName)) {
        throw new AnnotationNotFoundException($annotationInterfaceName, ReflectionUtils::getReflectorFileName($reflector));
      }
    }

    return new StandardAnnotationMetadata(new ExtendedReflectionClass($annotationInterfaceName));
  }

  private static function getAnnotationMetadataProxyForReflectionClass(\ReflectionClass $reflectionClass)
  {
    if (!$reflectionClass->implementsInterface(Annotation::class)) {
      throw new AnnotationBadConfigurationException($reflectionClass->getShortName(), ReflectionUtils::getReflectorFileName($reflectionClass));
    }

    $proxyClassName = '£Proxy' . (self::$count++);
    $classBuilder = ClassUtils::buildNewClass($proxyClassName)
      ->setNamespace('fall\\core\\proxy')
      ->implementInterface('\\' . $reflectionClass->getName())
      ->addProperty('memberValues', 'private', false, false, '[]')
      ->addProperty('type', 'private', false, false, '"' . $reflectionClass->getName() . '"');

    foreach ($reflectionClass->getMethods() as $reflectionClassMethod) {
      $classBuilder->addMethod($reflectionClassMethod->getName(), 'public', [], 'return $this->memberValues[\'' . $reflectionClassMethod->getName() . '\'];');
    }

    $classBuilder->build();
    return new ExtendedReflectionClass('fall\\core\\proxy\\' . $proxyClassName);
  }

  private static function getAnnotationMetadataParameters(string $annotationParameterString): array
  {
    $parameters = [];
    if (!empty($annotationParameterString)) {
      $annotationTemps = explode(',', $annotationParameterString);
      foreach ($annotationTemps as $annotationTemp) {
        $key = '_default';
        $value = $annotationTemp;
        if (strpos($annotationTemp, '=') > -1) {
          list($key, $value) = explode('=', $annotationTemp, 2);
          $key = trim($key);
          $value = trim($value);
        }

        switch (true) {
          case (preg_match("#\"([a-zA-Z0-9\%\.]+)\"#", $value, $matches)):
            $value = (string) $matches[1];
            break;

          case (preg_match("#([0-9]+)#", $value, $matches)):
            $value = (int) $matches[1];
            break;
        }

        $parameters[$key] = trim($value);
      }
    }

    return $parameters;
  }
}
