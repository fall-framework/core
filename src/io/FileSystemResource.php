<?php
declare(strict_types=1);


namespace fall\core\io;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class FileSystemResource extends AbstractResource
{

  private $file;
  private $inputStream = null;
  private $outputStream = null;

  public function __construct(File $file)
  {
    $this->file = $file;
  }

  public function contentLength(): int
  {
    return \filesize($this->getFilename());
  }

  public function exists(): bool
  {
    return $this->file->exists;
  }

  public function getInputStream(): InputStreamInterface
  {
    if ($this->inputStream === null) {
      $this->inputStream = new FileInputStream($this->file);
    }
    return $this->inputStream;
  }

  public function getOutputStream(): OutputStreamInterface
  {
    if ($this->outputStream === null) {
      $this->outputStream = new FileOutputStream($this->file);
    }
    return $this->outputStream;
  }

  public function getFile(): File
  {
    return $this->file;
  }

  public function getFilename(): string
  {
    return $this->file->getAbsolutePath();
  }

  public function isFile(): bool
  {
    return true;
  }

  public function isReadable(): bool
  {
    return \is_readable($this->getFilename());
  }
}
