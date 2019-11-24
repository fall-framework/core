<?php
declare(strict_types=1);


namespace fall\core\lang;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
interface ComparableInterface
{
  function compareTo(object $object): int;
}
