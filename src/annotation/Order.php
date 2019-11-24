<?php
declare(strict_types=1);


namespace fall\core\annotation;

use fall\core\lang\Annotation;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
interface Order extends Annotation
{
  function value();
}
