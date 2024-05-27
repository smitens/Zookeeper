<?php

require 'vendor/autoload.php'; // Autoload Composer dependencies

use Zooapp\App\Zookeeper\Zookeeper;

$zookeeper = new Zookeeper();
$zookeeper->run();