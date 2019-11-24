<?php
declare(strict_types=1);

namespace fall\core\utils\transformers\impl;

use fall\core\utils\transformers\TransformerInterface;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class DateTimeTransformer implements TransformerInterface {
  public function transform($value): \DateTime {
    $date = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
    $date->setTime(0, 0, 0);
    return $date;
  }
}
