<?php

declare(strict_types=1);

namespace fall\core\type;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
interface ClassMetadata
{
  /**
   * @return string
   */
  function getClassName(): string;

  /**
   * @return string[]
   */
  function getInterfaceNames(): array;

  /**
   * @return string
   */
  function getSuperClassName(): ?string;

  /**
   * @return bool
   */
  function hasSuperClass(): bool;

  /**
   * @return bool
   */
  function isAbstract(): bool;

  /**
   * @return bool
   */
  function isFinal(): bool;

  /**
   * @return bool
   */
  function isInterface(): bool;
}
