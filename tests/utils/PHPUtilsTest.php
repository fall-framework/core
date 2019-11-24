<?php

declare(strict_types=1);

namespace fall\core\utils;

use PHPUnit\Framework\TestCase;

/**
 * Test class for @see PHPUtils class
 * @author Angelis <angelis@users.noreply.github.com>
 */
class PHPUtilsTest extends TestCase
{
  public function testAddDynamicCode()
  {
    PHPUtils::addDynamicCode('<?php class Toto {}');
    $this->addToAssertionCount(1);
  }
}
