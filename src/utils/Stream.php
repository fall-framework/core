<?php
declare(strict_types=1);

namespace fall\core\utils;

class Stream {

  private $elements;

  private function __construct(array $elements) {
    $this->elements = $elements;
  }

  public function map(callable $callable): Stream {
    return new Stream(array_map($callable, $this->elements));
  }

  public function filter(callable $callable): Stream {
    return new Stream(array_filter($this->elements, $callable));
  }
  
  public function reduce(callable $callable): Stream {
    return new Stream(array_reduce($this->elements, $callable));
  }

  public function recursiveMap(callable $callable): Stream {
    $mergeFn = function($element, array &$array = []) use(&$callable, &$mergeFn): array {
      if (\is_array($element)) {
        foreach ($element as $e) {
          $mergeFn($e, $array);
        }
      } else {
        $array[] = $element;
        $mergeFn($callable($element), $array);
      }

      return $array;
    };

    return new Stream(array_filter($mergeFn($this->elements)));
  }

  public function each(callable $callable) {
    foreach ($this->elements as $element) {
      $callable($element);
    }
  }

  public function toArray(): array {
    return $this->elements;
  }

  public static function of(array $elements) {
    return new Stream($elements);
  }
}