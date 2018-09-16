<?php

Game::init();

function __autoload($class) {
    $class = "Lib/{$class}.php";
    echo "trying to load $class\n";
    require_once($class);
}
