<?php

class Commands {
    // protected for singleton pattern
    protected function __construct() {
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
            self::$instance = new Commands();
        }
        return self::$instance;
    }

    public function cmdSelectCharacter(Conn $connection) {
    }

    public function cmdCreateCharacter(Conn $connection) {
    }
}
