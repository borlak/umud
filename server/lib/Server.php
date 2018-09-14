<?php

require_once('Game.php');

class Server {
    private $port = null;
    private $socket = null;
    private $event_base = null;
    private $event = null;
    private $connections = null;
    private $buffers = null;
    private $id = 10;

    function __construct($port) {
        $this->port = $port;
    }

    public function start() {
        $this->connections = array();
        $this->buffers = array();
        $this->socket = stream_socket_server("tcp://0.0.0.0:{$this->port}", $errno, $errstr);

        stream_set_blocking($this->socket, 0);
        $this->event_base = event_base_new();
        $this->event = event_new();
        event_set($this->event, $this->socket, EV_READ | EV_PERSIST, 'Server::accept', $this->event_base);
        event_base_set($this->event, $this->event_base);
        event_add($this->event);
        event_base_loop($this->event_base);
    }

    /**
     * Close a connection and clear buffers.
     * @param int $connection_id
     */
    public function close($connection_id) {
        Game::getInstance()->removeConnection($connection_id);

        event_buffer_disable($this->buffers[$connection_id], EV_READ | EV_WRITE);
        event_buffer_free($this->buffers[$connection_id]);
        fclose($this->connections[$connection_id]);
        unset($this->buffers[$connection_id], $this->connections[$connection_id]);
    }

    public function accept($socket, $flag, $base) {
        $connection = stream_socket_accept($socket);
        $stat = fstat($connection);
        $descriptor = $stat['ino'];
        stream_set_blocking($connection, 0);

        $this->id += 1;

        $buffer = event_buffer_new($connection, 'Server::read', 'Server::write', 'Server::error', $descriptor);
        event_buffer_base_set($buffer, $base);
        event_buffer_timeout_set($buffer, 30, 30);
        event_buffer_watermark_set($buffer, EV_READ, 0, 0xffffff);
        event_buffer_priority_set($buffer, 10);
        event_buffer_enable($buffer, EV_READ | EV_WRITE | EV_PERSIST);

        $this->connections[$descriptor] = $connection;
        $this->buffers[$descriptor] = $buffer;

        Game::getInstance()->newConnection($descriptor);
    }

    private function error($buffer, $error, $id) {
        $this->close($id);
    }

    private function getBuffer($id) {
        return isset($this->buffers[$id]) ? $this->buffers[$id] : false;
    }

    private function read($buffer, $id) {
        while($read = event_buffer_read($buffer, 256)) {
            var_dump($read);
            $this->write("Got yo message", $id);
        }
    }

    private function write($message, $id) {
        if(($buffer = $this->getBuffer($id)) === false) {
            return;
        }

        if(!is_string($message) || empty($message)) {
            return;
        }

        event_buffer_write($buffer, $message);
    }
}
