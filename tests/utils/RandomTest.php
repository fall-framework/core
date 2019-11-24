<?php

declare(strict_types=1);

namespace fall\core\utils;

use PHPUnit\Framework\TestCase;

/**
 * Test class for @see Random class
 * @author Angelis <angelis@users.noreply.github.com>
 */
final class RandomTest extends TestCase
{
  public function testGenerateRandomAlpha(): void
  {
    $value = Random::generateRandomAlpha();

    $this->assertEquals(strlen($value), 10);
    $this->assertRegExp('#[a-zA-Z0-9]+#', $value);
  }
}
