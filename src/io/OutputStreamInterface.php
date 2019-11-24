<?php
declare(strict_types=1);


namespace fall\core\io;

interface OutputStreamInterface
{
  function write(array $target, int $offset = null, int $length = null): void;
}
