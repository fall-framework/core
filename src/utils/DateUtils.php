<?php
declare(strict_types=1);


namespace fall\core\utils;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
abstract class DateUtils
{
  private function __construct()
  { }

  public static function isSame(array $fields, \DateTime $firstDate, \DateTime $secondDate): bool
  {
    foreach ($fields as $field) {
      if ($firstDate->format($field) !== $secondDate->format($field)) {
        return false;
      }
    }

    return true;
  }

  public static function isSameYearMonthDay(\DateTime $firstDate, \DateTime $secondDate)
  {
    return DateUtils::isSame(['Y', 'm', 'd'], $firstDate, $secondDate);
  }

  public static function getDayTimestamp(\DateTime $date): int
  {
    $mignightDateTime = clone $date;
    $mignightDateTime->setTime(0, 0);
    return $date->getTimestamp() - $mignightDateTime->getTimestamp();
  }

  public static function isLeapYear(int $year): bool
  {
    return date('L', mktime(1, 1, 1, 1, 1, $year)) === 1;
  }
}
