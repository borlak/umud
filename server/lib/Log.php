<?php

class Log {
    /**
     * @var Log
     */
    private static $instance = null;

    // protected for singleton pattern
    protected function __construct() {
    }
    // for singleton pattern
    private function __clone() {
    }
    // for singleton pattern
    private function __wakeup() {
    }
    /**
     * @return Log
     */
    public static function log($mesg)
    {
        echo $mesg.PHP_EOL;

        if(!isset(self::$instance)) {
            self::$instance = new Log();
        }
        return self::$instance;
    }
}
