<?php
declare(strict_types=1);


namespace fall\core\io;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
interface WriterInterface
{
  function write(array $target, int $offset = null, int $length = null): void;
  function writeLine(string $line): void;
}
