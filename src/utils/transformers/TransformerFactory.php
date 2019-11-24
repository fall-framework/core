<?php
declare(strict_types=1);

namespace fall\core\utils\transformers;

use fall\core\utils\SingletonTrait;
use fall\core\utils\transformers\exceptions\TransformationException;
use fall\core\utils\transformers\impl\BooleanTransformer;
use fall\core\utils\transformers\impl\DateTimeTransformer;
use fall\core\utils\transformers\impl\DateTransformer;
use fall\core\utils\transformers\impl\FloatTransformer;
use fall\core\utils\transformers\impl\IntegerTransformer;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class TransformerFactory {

  use SingletonTrait;

  private $transformers = [];

  private function __construct() {
    $this->addTransformer(array("boolean", "bool"), BooleanTransformer::class);
    $this->addTransformer("datetime", DateTimeTransformer::class);
    $this->addTransformer("date", DateTransformer::class);
    $this->addTransformer("float", FloatTransformer::class);
    $this->addTransformer(array("integer", "int"), IntegerTransformer::class);
  }

  /**
   * Add a transformer class
   * @param $type (array|string)
   * @param string TransformerInterface
   */
  public function addTransformer($type, string $transformer, $overload = false): void {
    if (is_array($type)) {
      foreach($type as $t) {
        $this->addTransformer($t, $transformer, $overload);
      }
      return;
    }

    if (isset($this->transformers[$type]) && !$overload) {
      throw new TransformationException("Transformer already exist");
    }

    $this->transformers[$type] = new $transformer();
  }

  public function getTransformer($type) {
    if (!isset($this->transformers[$type])) {
      return null;
    }

    return $this->transformers[$type];
  }
}