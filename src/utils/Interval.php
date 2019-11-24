<?php
declare(strict_types=1);


namespace fall\core\utils;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class Interval
{

  private $start;
  private $end;

  public function __construct(\DateTime $start, \DateTime $end)
  {
    $this->start = $start;
    $this->end = $end;
  }

  /**
   * Return true if the $date is after this interval end date
   */
  public function before(\DateTime $date)
  {
    return $this->end->getTimestamp() < $date->getTimestamp();
  }

  /**
   * Return true if the $date is after or equals this interval end date
   */
  public function beforeOrEquals(\DateTime $date)
  {
    return $this->end->getTimestamp() <= $date->getTimestamp();
  }

  /**
   * Return true if the $date is before this interval start date
   */
  public function after(\DateTime $date)
  {
    return $date->getTimestamp() < $this->start->getTimestamp();
  }

  /**
   * Return true if the $date is before or equals this interval start date
   */
  public function afterOrEquals(\DateTime $date)
  {
    return $date->getTimestamp() <= $this->start->getTimestamp();
  }

  public function getStart()
  {
    return $this->start;
  }

  public function getEnd()
  {
    return $this->end;
  }
}
