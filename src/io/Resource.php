<?php
declare(strict_types=1);


namespace fall\core\io;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
interface Resource
{
  function contentLength(): int;
  function exists(): bool;
  function getFile(): File;
  function getFilename(): string;
  function isFile(): bool;
  function isOpen(): bool;
  function isReadable(): bool;
}
