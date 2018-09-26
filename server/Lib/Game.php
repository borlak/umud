<?php

class Game {
    /**
     * @var Game
     */
    private static $instance = null;
    /**
     * @var Conn[]
     */
    private $connections;
    /**
     * @var Server
     */
    private $server;
    /**
     * @var Area[]
     */
    private $areas;

    // protected for singleton pattern
    protected function __construct() {
        $this->connections = array();
    }
    // for singleton pattern
    private function __clone() {
    }
    // for singleton pattern
    private function __wakeup() {
    }
    /**
     * @return Game
     */
    public static function getInstance()
    {
        if(!isset(self::$instance)) {
            self::$instance = new Game();
        }
        return self::$instance;
    }

    /**
     * Initialize the Game.  Start the Server which accepts connections.
     */
    public static function init() {
        // Initialize singleton
        $Game = new Game();
        self::$instance = $Game;

        // Load Areas
        for($x = 0; $x < 40; $x++) {
            $Game->addArea(new Area());
        }
        Log::log(Log::DEBUG, "Total rooms loaded: ".$Game->roomCount());

        echo "Current PHP Memory usage:".memory_get_usage().PHP_EOL;

        $Area = $Game->getArea(0);
        $map = $Area->getMap(20,20,20,20);
        $Area->drawAsciiMap($map);
        die;

        // Start the socket server
        $Server = new Server(2000);
        $Game->setServer($Server);
        $Server->start();
    }

    /**
     * Sets up the Server that processes connections.  Needed for calling write() from the Connections
     * @param Server $server
     */
    public function setServer($server) {
        $this->server = $server;
    }

    /**
     * @return Area
     */
    public function getArea($index) {
        return $this->areas[$index];
    }

    public function addArea(Area $area) {
        $this->areas[] = $area;
    }

    /**
     * Get total number of rooms for the Game.
     * @return int
     */
    public function roomCount() {
        $total = 0;
        foreach($this->areas as $area) {
            $total += $area->roomCount();
        }
        return $total;
    }

    public function newConnection($id) {
        $connection = new Conn($id, $this->server);
        $connection->setState(Conn::STATE_CONNECTED);
        $this->connections[] = $connection;
        $connection->send('Welcome to the game');
        Log::log(Log::DEBUG, "New connection $id");
    }

    public function removeConnection($id) {
        unset($this->connections[$id]);
        Log::log(Log::DEBUG, "Lost connection $id");
    }
}
