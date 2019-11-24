<?php

declare(strict_types=1);

namespace fall\core\io;

use PHPUnit\Framework\TestCase;

/**
 * Test class for @see File class
 * 
 * @author Angelis <angelis@users.noreply.github.com>
 */
final class FileTest extends TestCase
{
  const FILE_NAME = 'test';
  private $file;

  public function setUp()
  {
    $tempDir = sys_get_temp_dir();
    $this->file = new File($tempDir . DIRECTORY_SEPARATOR . self::FILE_NAME);
  }

  public function testConstruct()
  {
    $this->assertNotNull($this->file);
    $this->assertFalse($this->file->exists());
    $this->assertFalse($this->file->isDirectory());
  }

  public function testGetName()
  {
    $this->assertEquals(self::FILE_NAME, $this->file->getName());
  }
}
