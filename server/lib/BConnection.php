<?php

require_once('Server.php');

class BConnection {
    const STATE_NONE = 0;
    const STATE_CONNECTED = 1;

    private $id;
    private $state;
    /**
     * @var Server
     */
    private $server;

    function __construct($id, $server) {
        $this->id = $id;
        $this->state = self::STATE_NONE;
        $this->server = $server;
    }

    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function send($message) {
        var_dump($this->server);
        $this->server->write($message, $this->id);
    }
}
