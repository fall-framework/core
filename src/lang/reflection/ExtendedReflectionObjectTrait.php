<?php

declare(strict_types=1);

namespace fall\core\lang\reflection;

use fall\core\utils\Stream;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
trait ExtendedReflectionObjectTrait
{
  private $annotationsMetadata;
  private $annotations;

  /**
   * @return AnnotationMetadata[]
   */
  public function getAnnotationsMetadata(): array
  {
    if ($this->annotationsMetadata === null) {
      $this->annotationsMetadata = $this->_getAnnotationsMetadata();
    }

    return $this->annotationsMetadata;
  }

  /**
   * @return Annotation[]
   */
  public function getAnnotations(): array
  {
    if ($this->annotations === null) {
      $this->annotations = [];

      foreach ($this->getAnnotationsMetadata() as $annotationMetadata) {
        $this->annotations[] = $annotationMetadata->build();
      }
    }

    return $this->annotations;
  }

  /**
   * @return Annotation
   */
  public function getAnnotation(string $annotationClass): ?Annotation
  {
    foreach ($this->getAnnotations() as $annotation) {
      $annotationClassImplements = class_implements($annotation);
      if (\in_array($annotationClass, $annotationClassImplements)) {
        return $annotation;
      }
    }
    return null;
  }

  /**
   * @return bool
   */
  public function isAnnotationPresent(string $annotationClass): bool
  {
    foreach ($this->getAnnotationsMetadata() as $annotationMetadata) {
      if ($annotationMetadata->getAnnotationExtendedReflectionClass()->getName() === $annotationClass) {
        return true;
      }

      $parentContainingAnnotation = Stream::of($annotationMetadata->getParentAnnotationMetadata())
        ->filter(function ($element) use ($annotationClass) {
          return $element->getAnnotationExtendedReflectionClass()->getName() === $annotationClass;
        })
        ->toArray();

      if (!empty($parentContainingAnnotation)) {
        return true;
      }
    }

    return false;
  }

  protected abstract function _getAnnotationsMetadata(): array;
}
