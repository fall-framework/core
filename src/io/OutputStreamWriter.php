<?php
declare(strict_types=1);


namespace fall\core\io;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class OutputStreamWriter implements WriterInterface
{
  private $outputStream;

  public function __construct(OutputStreamInterface $outputStream)
  {
    $this->outputStream = $outputStream;
  }

  public function write(array $target, int $offset = null, int $length = null): void
  {
    $this->outputStream->write($target, $offset, $length);
  }

  public function writeLine(string $line): void
  {
    $this->write(\str_split($line));
  }
}
