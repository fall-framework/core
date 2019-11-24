<?php
declare(strict_types=1);


namespace fall\core\io;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class BufferedWriter implements WriterInterface
{
  private $writer;

  public function __construct(WriterInterface $writer)
  {
    $this->writer = $writer;
  }

  public function write(array $target, int $offset = null, int $length = null): void
  {
    $this->writer->write($target, $offset, $length);
  }

  public function writeLine(string $line): void
  {
    $this->write(\str_split($line));
  }
}
