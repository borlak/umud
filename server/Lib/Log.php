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
     * Log something.  If the set level is greater than $level, then nothing will log.
     * This function accepts extra arguments, and will sprintf() the mesg with the extra
     * arguments.
     * @param int $level see class constants
     * @param string $mesg the message to log
     * @param ... any extra arguments will get sprintf()'d
     * @return Log
     */
    public static function log($level, $mesg)
    {
        if(!is_numeric($level)) {
            echo "Log level is not numeric\n";
            return;
        }
        if($level < self::$log_level) {
            return;
        }

        if(count($args = func_get_args()) > 2) {
            echo vsprintf($mesg, array_slice($args,2)).PHP_EOL;
        } else {
            echo $mesg.PHP_EOL;
        }
    }
}
