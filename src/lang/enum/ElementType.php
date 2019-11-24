<?php
declare(strict_types=1);


namespace fall\core\lang\enum;

use fall\core\lang\Enum;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
abstract class ElementType extends Enum
{
  public const CONSTRUCTOR = 0;
  public const METHOD = 1;
  public const PROPERTY = 2;
  public const PARAMETER = 3;
  public const NAMESPACE = 4;
  public const TYPE = 5;
}
