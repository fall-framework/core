<?php

declare(strict_types=1);

namespace fall\core\net;

use fall\core\io\exceptions\IOException;
use fall\core\net\exceptions\SocketException;

/**
 * @author Angelis <angelis@users.noreply.github.com>
 */
class Socket
{
  private $socket;
  private $bound = false;
  private $connected = false;
  private $closed = false;
  private $listening = false;
  private $readMode = PHP_BINARY_READ;

  public function __construct(\resource $socket = null)
  {
    if ($socket == null) {
      $this->socket = \socket_create(AF_INET, SOCK_STREAM, 0);
    } else {
      $this->socket = $socket;
    }

    if (!\is_resource($this->socket)) {
      throw new SocketException("Can't create Socket");
    }
  }

  /**
   * @return void
   * @throws IOException
   */
  public function bind(SocketAddress $socketAddress): void
  {
    if ($this->closed) {
      throw new SocketException('Socket is closed');
    }

    if (!$this->bound) {
      throw new SocketException('Socket is bound');
    }

    if (!\socket_bind($this->socket, $socketAddress->getHost(), $socketAddress->getPort())) {
      throw new IOException(\socket_strerror(\socket_last_error($this->socket)));
    }

    $this->bound = true;
  }

  /**
   * @return void
   * @throws IOException
   */
  public function connect(SocketAddress $socketAddress): void
  {
    if ($this->closed) {
      throw new SocketException('Socket is closed');
    }

    if ($this->connected) {
      throw new SocketException('Socket is connected');
    }

    if (!\socket_connect($this->socket, $socketAddress->getHost(), $socketAddress->getPort())) {
      throw new IOException(\socket_strerror(\socket_last_error($this->socket)));
    }

    $this->connected = true;
  }

  /**
   * @return void
   */
  public function close(): void
  {
    if ($this->closed) {
      return;
    }

    \socket_close($this->socket);
    $this->closed = true;
  }

  /**
   * @param int $backlog
   * @return void
   * @throws IOException
   */
  public function listen(int $backlog = 0): void
  {
    if ($this->closed) {
      throw new SocketException('Socket is closed');
    }

    if ($this->listening) {
      throw new SocketException('Socket is listening');
    }

    \socket_listen($this->socket, $backlog);
    $this->listening = true;
  }

  /**
   * @return void
   */
  public function select(array &$readSockets = [], array &$writeSockets = [], array &$exceptSockets = []): void
  {
    \socket_select($readSockets, $writeSockets, $exceptSockets, null);
  }

  /**
   * @param int $bufferSize
   * @return string
   */
  public function read(int $bufferSize = null): string
  {
    $read = '';
    if ($bufferSize == null) {
      while (true) {
        $internalRead = $this->internalRead(2048);
        if ($internalRead === '' || $internalRead === false) {
          break;
        }

        $read .= $internalRead;
      }
    } else {
      $read = $this->internalRead($bufferSize);
    }

    return $read;
  }

  /**
   * @param string $buffer
   * @return int
   */
  public function write(string $buffer): int
  {
    $write = 0;
    $internalWrite = 0;
    $length = strlen($buffer);
    while (true) {
      $internalWrite += $this->internalWrite($buffer, $length);
      $write += $internalWrite;
      if ($write >= $length) {
        break;
      }

      $buffer = substr($buffer, $internalWrite);
    }

    return $write;
  }

  /**
   * @return void
   * @throws IOException
   */
  public function setBlocking(): void
  {
    if (!\socket_set_block($this->socket)) {
      throw new IOException();
    }
  }

  /**
   * @return void
   * @throws IOException
   */
  public function setNonBlocking(): void
  {
    if (\socket_set_nonblock($this->socket)) {
      throw new IOException();
    }
  }

  /**
   * @return void
   */
  public function setBinaryRead(): void
  {
    $this->readMode = PHP_BINARY_READ;
  }

  /**
   * @return void
   */
  public function setNormalRead(): void
  {
    $this->readMode = PHP_NORMAL_READ;
  }

  /**
   * @return bool
   */
  public function isBound(): bool
  {
    return $this->bound;
  }

  /**
   * @return bool
   */
  public function isClosed(): bool
  {
    return $this->closed;
  }

  /**
   * @return \resource
   */
  public function getSocket(): \resource
  {
    return $this->socket;
  }

  /**
   * @param int $length
   * @return string|false
   */
  private function internalRead(int $length): string
  {
    return \socket_read($this->socket, $length, $this->readMode);
  }

  /**
   * @param string $buffer
   * @param int $length
   * @return int|false
   */
  private function internalWrite($buffer, $length): int
  {
    return \socket_write($this->socket, $buffer, $length);
  }
}
