<?php
declare(strict_types=1);


namespace fall\core\utils;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
abstract class FileUtils
{

  private function __constuct()
  { }

  /**
   * @param $directoryPath string
   * @param $recursive boolean - tell if the sub folders must be scanned
   * @param $filters array<string> - list of file extensions
   */
  public static function listDirectoryFiles($directoryPath, $recursive = false, $filters = [])
  {
    $directoryFiles = [];
    $fileNames = array_diff(scandir($directoryPath), array('.', '..'));
    foreach ($fileNames as $fileName) {
      $file = $directoryPath . DIRECTORY_SEPARATOR . $fileName;
      if (is_file($file) && in_array(FileUtils::getFileExtension($fileName), $filters)) {
        $directoryFiles[] = $file;
      }

      if (is_dir($file) && $recursive) {
        $directoryFiles = array_merge($directoryFiles, FileUtils::listDirectoryFiles($file, $recursive, $filters));
      }
    }
    return $directoryFiles;
  }

  /**
   * @param $fileName string
   * @return string
   */
  public static function getFileExtension($fileName)
  {
    $position = strrpos($fileName, ".");
    if ($position === false) {
      return $fileName;
    }

    return substr($fileName, $position + 1);
  }
}
