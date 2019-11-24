<?php
declare(strict_types=1);


namespace fall\core\utils;

use fall\core\io\File;
use fall\core\io\FileWriter;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
abstract class PHPUtils
{

  private function __construct()
  { }

  /**
   * @param $fileName string
   */
  public static function getAllFileUses($fileName)
  {
    $fileUses = [];
    $tokens = token_get_all(file_get_contents($fileName));
    $inUse = false;
    $currentUse = '';
    foreach ($tokens as $token) {
      if (!$inUse) {
        if ($token[0] == T_USE) {
          $inUse = true;
        }
        continue;
      }

      if ($token === '(') {
        $inUse = false;
        continue;
      }

      if ($token === " ") {
        continue;
      }

      if ($token === ";") {
        $fileUses[substr($currentUse, strrpos($currentUse, '\\') + 1)] = $currentUse;
        $currentUse = '';
        $inUse = false;
        continue;
      }

      $currentUse .= trim($token[1]);
    }

    return $fileUses;
  }

  /**
   * @param $fileName string
   */
  public static function getFileNamespace($fileName)
  {
    $tokens = token_get_all(file_get_contents($fileName));
    $inNamespace = false;
    $currentNamespace = '';
    foreach ($tokens as $token) {
      if (!$inNamespace) {
        if ($token[0] == T_NAMESPACE) {
          $inNamespace = true;
        }
        continue;
      }

      if ($token == ";") {
        break;
      }

      $currentNamespace .= trim($token[1]);
    }

    return $currentNamespace;
  }

  public static function namespaceToPath($namespace)
  {
    str_replace($namespace, "\\", DIRECTORY_SEPARATOR);
  }

  public static function addDynamicCode($code)
  {
    try {
      $file = File::createTempFile('php
declare(strict_types=1);
class');
      $fileWriter = new FileWriter($file);
      $fileWriter->writeLine($code);
      require $file->getAbsolutePath();
    } catch (\Exception $e) {
      // Nothing to do
    }
  }
}
