<?php
declare(strict_types=1);

namespace fall\core\utils\transformers\impl;

use fall\core\utils\transformers\TransformerInterface;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class IntegerTransformer implements TransformerInterface {
  public function transform($value): int {
    return (int) $value;
  }
}
