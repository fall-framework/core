<?php
declare(strict_types=1);

namespace fall\core\utils\transformers\exceptions;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class TransformationException extends \Exception {
  public function __construct($message) {
    parent::__construct($message);
  }
}
