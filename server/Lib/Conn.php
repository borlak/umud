<?php

class Conn {
    const STATE_NONE = 0;
    const STATE_CONNECTED = 1;

    private $id;
    private $state;
    private $base;
    /**
     * @var EventBufferEvent
     */
    private $bev;

    /**
     * @param int $id descriptor
     * @param Lib\Server $server
     */
    function __construct($base, $fd) {
        $this->id = $fd;
        $this->state = self::STATE_NONE;
        $this->base = $base;

        $this->bev = new EventBufferEvent($base, $fd, EventBufferEvent::OPT_CLOSE_ON_FREE);

        $this->bev->setCallbacks(
            array($this, 'read'),
            NULL,
            array($this, 'error'),
            NULL
        );

        if (!$this->bev->enable(Event::READ)) {
            echo "Failed to enable READ\n";
            return;
        }
    }

    public function __destruct() {
        $this->bev->free();
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

    public function read($bev, $arg) {
        $read = '';
        while(strlen($text = $bev->read(1024))) {
            $read .= $text;
        }
        Log::log(Log::DEBUG, "Socket $this->id read: $read\n");
        return $read;
    }

    public function write($string) {
        Log::log(Log::DEBUG, "Socket $this->id writing: $string\n");
        return $this->bev->write($string);
    }

    public function error($bev, $events, $ctx) {
        if($events & EventBufferEvent::ERROR) {
            echo "Error from bufferevent for descriptor {$this->id}\n";
        }

        if ($events & (EventBufferEvent::EOF | EventBufferEvent::ERROR | EventBufferEvent::TIMEOUT)) {
            echo "{$this->id} disconnects\n";
            $this->__destruct();
        }
    }
}
