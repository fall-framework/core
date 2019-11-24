<?php
declare(strict_types=1);

namespace fall\core\utils\transformers;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
interface TransformerInterface {
  public function transform($value);
}