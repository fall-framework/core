<?php
declare(strict_types=1);


namespace fall\core\io;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
interface ReaderInterface
{
  function read(array &$buffer, int $offset = null, int $length = null): int;
  function readLine(): ?string;
}
