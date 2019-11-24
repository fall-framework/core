<?php
declare(strict_types=1);


namespace fall\core\lang;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class MethodBuilder
{
  private $methodName;
  private $visibility;
  private $classBuilder;

  public function __construct(string $methodName, ClassBuilder $classBuilder)
  {
    $this->methodName = $methodName;
  }

  public function setVisibility()
  { }
}
