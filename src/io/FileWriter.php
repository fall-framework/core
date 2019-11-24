<?php
declare(strict_types=1);


namespace fall\core\io;

use fall\core\io\exceptions\IOException;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class FileWriter extends OutputStreamWriter
{
  public function __construct(File $file)
  {
    if (!$file->exists() || $file->isDirectory()) {
      throw new IOException();
    }

    parent::__construct(new FileOutputStream($file));
  }
}
