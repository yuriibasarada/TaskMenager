<?php

require_once 'vendor/autoload.php';
$env =
$loop = \React\EventLoop\Factory::create();
$mysql = new \React\MySQL\Factory($loop);
