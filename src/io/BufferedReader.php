<?php
declare(strict_types=1);


namespace fall\core\io;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class BufferedReader implements ReaderInterface
{
  private $reader;

  public function __construct(ReaderInterface $reader)
  {
    $this->reader = $reader;
  }

  public function read(array &$buffer, int $offset = null, int $length = null): int
  {
    return $this->reader->read($buffer, $offset, $length);
  }

  public function readLine(): ?string
  {
    return $this->reader->readLine();
  }
}
