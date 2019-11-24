<?php

declare(strict_types=1);

namespace fall\core\net;

use fall\core\io\exceptions\IOException;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class ServerSocket
{
  private $socket;

  public function __construct(SocketAddress $socketAddress, int $backlog = 10)
  {
    $this->socket = new Socket();
    $this->socket->setNonBlocking();
    $this->socket->bind($socketAddress);
    $this->socket->setNonBlocking($backlog);
  }

  /**
   * @return Socket
   * @throws IOException
   */
  public function accept(): Socket
  {
    $socket = \socket_accept($this->socket->getSocket());
    if ($socket === false) {
      throw new SocketException("Can't accept");
    }

    return new Socket($socket);
  }

  /**
   * @return vois
   */
  public function listen(int $backlog): void
  {
    $this->socket->listen($backlog);
  }

  /**
   * @return void
   */
  public function bind(SocketAddress $socketAddress): void
  {
    $this->socket->bind($socketAddress);
  }

  /**
   * @return void
   */
  public function select(&$readSockets = [], &$writeSockets = [], &$exceptSockets = []): void
  {
    if (!\socket_select($readSockets, $writeSockets, $exceptSockets, null)) {
      throw new IOException(\socket_strerror(\socket_last_error($this->socket)));
    }
  }

  /**
   * @return bool
   */
  public function isBound(): bool
  {
    return $this->socket->isBound();
  }

  /**
   * @return bool
   */
  public function isClosed(): bool
  {
    return $this->socket->isClosed();
  }

  /**
   * @return Socket
   */
  public function getSocket(): Socket
  {
    return $this->socket;
  }

  /**
   * @return \resource
   */
  public function getSocketResource()
  {
    return $this->socket->getSocket();
  }
}
