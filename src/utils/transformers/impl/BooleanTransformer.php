<?php
declare(strict_types=1);

namespace fall\core\utils\transformers\impl;

use fall\core\utils\transformers\TransformerInterface;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class BooleanTransformer implements TransformerInterface {
  public function transform($value): bool {
    return (bool) $value;
  }
}
