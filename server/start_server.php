<?php

require_once('Lib/Constants.php');

Log::create(Log::DEBUG);
Game::init();

function __autoload($class) {
    $class = "Lib/{$class}.php";
    require_once($class);
}
