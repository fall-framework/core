<?php

declare(strict_types=1);

namespace fall\core\cache\impl;

use DateInterval;
use DateTime;
use fall\core\cache\CacheItemInterface;

class CacheItem implements CacheItemInterface
{
  private $key;
  private $value;
  private $expiration;

  public function __construct(string $key, string $value = null)
  {
    $this->key = $key;
    $this->value = $value;
  }

  public function getKey(): string
  {
    return $this->key;
  }

  public function get(): ?mixed
  {
    if (!$this->isHit()) {
      return null;
    }

    return $this->value;
  }

  public function isHit(): bool
  {
    return isset($this->value);
  }

  public function set($value): CacheItem
  {
    return $this;
  }

  public function expiresAt(\DateTimeInterface $expiration): CacheItem
  {
    if ($expiration === null) {
      $expiration = (new DateTime(''))->add(new DateInterval('PT1H'));
    }

    $this->expiration = $expiration;
    return $this;
  }

  public function expiresAfter($time): CacheItem
  {
    return $this;
  }
}
