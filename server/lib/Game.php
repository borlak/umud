<?php

require_once('Log.php');
require_once('BConnection.php');

class Game {
    /**
     * @var Game
     */
    private static $instance = null;
    /**
     * @var BConnection
     */
    private $connections;

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
    public static function getInstance()
    {
        if(!isset(self::$instance)) {
            self::$instance = new Game();
        }
        return self::$instance;
    }

    public function newConnection($id) {
        $this->connections[] = new BConnection($id);
        Log::log("New connection $id");
    }

    public function removeConnection($id) {
        unset($this->connections[$id]);
        Log::log("Lost connection $id");
    }
}
