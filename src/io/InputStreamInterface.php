<?php
declare(strict_types=1);


namespace fall\core\io;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
interface InputStreamInterface
{
  function read(array &$buffer, int $offset = null, int $length = null): int;
  function skip(int $n): int;
}
