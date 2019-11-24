<?php
declare(strict_types=1);


namespace fall\core\io;

use fall\core\lang\exceptions\NullPointerException;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class File
{
  private $path;

  public function __construct(string $path)
  {
    if ($path === null) {
      throw new NullPointerException();
    }

    $this->path = str_replace('\\', DIRECTORY_SEPARATOR, $path);
  }

  public function create(): bool
  {
    return \touch($this->path);
  }

  public function delete(): bool
  {
    return \unlink($this->path);
  }

  public function getName(): string
  {
    $separatorLastPosition = \strrpos($this->path, DIRECTORY_SEPARATOR);
    if ($separatorLastPosition === false) {
      return '';
    }
    return \substr($this->path, $separatorLastPosition + 1);
  }

  public function getAbsolutePath(): string
  {
    return $this->path;
  }

  public function isFile(): bool
  {
    return \is_file($this->path);
  }

  public function isDirectory(): bool
  {
    return \is_dir($this->path);
  }

  public function mkdir(): bool
  {
    return \mkdir($this->path);
  }

  public function exists(): bool
  {
    return \file_exists($this->path);
  }

  public static function createTempFile(string $prefix, File $parent = null): File
  {
    if ($parent === null) {
      $dir = \sys_get_temp_dir();
    } else {
      $dir = $parent->getAbsolutePath();
    }

    return new File(\tempnam($dir, $prefix));
  }
}
