<?php
declare(strict_types=1);


namespace fall\core\lang\annotation;

use fall\core\lang\Annotation;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
interface DefaultValue extends Annotation
{
  public function value();
}
