<?php

class Conn {
    const STATE_NONE = 0;
    const STATE_CONNECTED = 1;

    private $id;
    private $state;
    /**
     * @var Server
     */
    private $server;

    /**
     * @param int $id descriptor
     * @param Lib\Server $server
     */
    function __construct(int $id, Server $server) {
        $this->id = $id;
        $this->state = self::STATE_NONE;
        $this->server = $server;
    }

    /**
     * Get state of connection.  See STATE_ constants.
     * @return int
     */
    public function getState() {
        return $this->state;
    }

    /**
     * Set the state of connection.  See STATE_ constants
     */
    public function setState(int $state) {
        $this->state = $state;
    }

    /**
     * Send a message/string to the connection.
     */
    public function send(string $message) {
        return $this->server->write($message, $this->id);
    }
}
