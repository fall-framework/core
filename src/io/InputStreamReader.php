<?php
declare(strict_types=1);


namespace fall\core\io;

class InputStreamReader implements ReaderInterface
{

  private $inputStream;

  public function __construct(InputStreamInterface $inputStream, string $encoding)
  {
    $this->inputStream = $inputStream;
  }

  public function read(array &$buffer, int $offset = null, int $length = null): int
  {
    return $this->inputStream->read($buffer, $offset, $length);
  }

  public function readLine(): ?string
  {
    $line = '';

    $character = [];
    while ($this->read($character, null, 1) > 0) {
      $line .= $character[0];

      if ($character[0] === "\n") {
        break;
      }
    }

    $line = \trim($line);
    if ($line === '') {
      return null;
    }

    return $line;
  }
}
