<?php
declare(strict_types=1);


namespace fall\core\io;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class FileOutputStream implements OutputStreamInterface
{
  private $resource;

  public function __construct(File $file)
  {
    $this->resource = \fopen($file->getAbsolutePath(), 'w');
  }

  public function write(array $buffer, int $offset = null, int $length = null): void
  {
    if ($offset !== null) {
      \fseek($this->resource, $offset);
    }

    fwrite($this->resource, implode('', $buffer));
  }
}
