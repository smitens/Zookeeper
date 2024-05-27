<?php

require 'vendor/autoload.php';

use Zooapp\App\Zookeeper\Zookeeper;

$zookeeper = new Zookeeper();
$zookeeper->run();