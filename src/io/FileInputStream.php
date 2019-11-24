<?php
declare(strict_types=1);


namespace fall\core\io;

use fall\core\io\exceptions\FileNotFoundException;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class FileInputStream implements InputStreamInterface
{
  private $resource;

  public function __construct(File $file)
  {
    if (!$file->exists() || $file->isDirectory()) {
      throw new FileNotFoundException();
    }

    $this->resource = \fopen($file->getAbsolutePath(), 'r');
  }

  public function read(array &$buffer, int $offset = null, int $length = null): int
  {
    if ($offset !== null) {
      \fseek($this->resource, $offset);
    }

    $string = fread($this->resource, $length);
    if (empty($string)) {
      return 0;
    }

    for ($i = 0; $i < $length; $i++) {
      $buffer[$i] = $string[$i];
    }

    return $i;
  }

  public function skip(int $n): int
  {
    $skipped = 0;
    while (!\feof($this->resource) && $skipped < $n) {
      fgetc($this->resource);
      $skipped++;
    }
    return $skipped;
  }
}
