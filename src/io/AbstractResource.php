<?php
declare(strict_types=1);


namespace fall\core\io;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
abstract class AbstractResource implements Resource
{
  public function isFile(): bool
  {
    return false;
  }

  public function isOpen(): bool
  {
    return false;
  }
}
