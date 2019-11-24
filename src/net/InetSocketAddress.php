<?php
declare(strict_types=1);

namespace fall\core\net;

use fall\core\net\SocketAddress;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class InetSocketAddress extends SocketAddress {

  private $host;
  private $port;

  public function __construct($host, $port) {
    $this->host = $host;
    $this->port = $port;
  }

  public function getHost() {
    return $this->host;
  }

  public function getPort() {
    return $this->port;
  }

  public static function createUnresolved($host, $port) {
    return new InetSocketAddress($host, $port);
  }
}
