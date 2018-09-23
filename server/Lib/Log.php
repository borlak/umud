<?php

class Log {
    const DEBUG = 1;
    const WARN = 2;
    const ERROR = 3;

    private static $log_level = self::DEBUG;
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
     * Create the singleton
     * @param int $log_level what level of logging?
     */
    public static function create($log_level) {
        self::$log_level = $log_level;
        if(!isset(self::$instance)) {
            self::$instance = new Log();
        }
        return self::$instance;
    }

    /**
     * @return Log
     */
    public static function log($level, $mesg)
    {
        if($level < self::$log_level) {
            return;
        }

        echo $mesg.PHP_EOL;
    }
}
