<?php
declare(strict_types=1);


namespace fall\core\lang;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
trait EventFlowTrait
{
  private $events = [];

  protected function on($eventKey, $callback)
  {
    if (!isset($this->events[$eventKey])) {
      $this->events[$eventKey] = [];
    }

    $this->events[$eventKey][] = $callback;
  }

  protected function trigger($eventKey, array $params = [])
  {
    if (!isset($this->events[$eventKey]) || empty($this->events[$eventKey])) {
      return;
    }

    foreach ($this->events[$eventKey] as $event) {
      call_user_func_array($event, $params);
    }
  }
}
