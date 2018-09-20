<?php

class Server {
    private $port = null;
    private $server = null;
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

        $this->base = new EventBase();
        $this->server = new EventListener(
            $this->base,
            array($this,'accept'), $this->base,
            EventListener::OPT_CLOSE_ON_FREE | EventListener::OPT_REUSEABLE, -1,
            "0.0.0.0:{$this->port}"
        );

        if(!$this->server) {
            echo "Couldn't create socket server\n";
            exit(1);
        }

        $this->server->setErrorCallback(array($this, 'error'));

        $this->base->dispatch();
    }

    public function accept($server, $fd, $address, $ctx) {
        $base = $this->base;
        $this->connections[] = new Conn($base, $fd);
    }

    public function error($server, $ctx) {
        $base = $this->base;

        fprintf(STDERR, "Got an error %d (%s) on the listener. Shutting down.\n",
            EventUtil::getLastSocketErrno(),
            EventUtil::getLastSocketError());

        $base->exit(NULL);
    }
}
