<?php
declare(strict_types=1);


namespace fall\core\utils;

use fall\core\lang\ClassBuilder;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
abstract class ClassUtils
{
  private function __construct()
  { }

  public static function buildNewClass($className, $namespace = null): ClassBuilder
  {
    return new ClassBuilder($className, $namespace);
  }

  public static function loadAllClassInDirectory($directory, $recursive = false): void
  {
    $handle = opendir($directory);
    while (false !== ($entry = readdir($handle))) {
      if ($entry === '.' || $entry === '..') {
        continue;
      }

      $fileFullPath = $directory . DIRECTORY_SEPARATOR . $entry;
      switch (true) {
        case (is_file($fileFullPath)):
          $splFileInfo = new \SplFileInfo($fileFullPath);
          if ($splFileInfo->getExtension() === 'php
declare(strict_types=1);
') {
            require_once $fileFullPath;
          }
          break;

        case (is_dir($fileFullPath)):
          if ($recursive) {
            ClassUtils::loadAllClassInDirectory($fileFullPath, $recursive);
          }
          break;

        default:
      }
    }
  }
}
