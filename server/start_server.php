<?php

require_once('lib/Server.php');
require_once('lib/Log.php');

$Server = new Server(2000);
$Server->start();
