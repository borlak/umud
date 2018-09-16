<?php

class Game {
    /**
     * @var Game
     */
    private static $instance = null;
    /**
     * @var array Conn
     */
    private $connections;
    /**
     * @var Server
     */
    private $server;

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
        $Server = new Server(2000);
        $Game = new Game();
        self::$instance = $Game;
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

    public function newConnection($id) {
        $connection = new Conn($id, $this->server);
        $connection->setState(Conn::STATE_CONNECTED);
        $this->connections[] = $connection;
        $connection->send('Welcome to the game');
        Log::log("New connection $id");
    }

    public function removeConnection($id) {
        unset($this->connections[$id]);
        Log::log("Lost connection $id");
    }
}
