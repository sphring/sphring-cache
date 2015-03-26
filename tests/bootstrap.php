<?php
include_once __DIR__ . '/config.php';
$loader = require_once __DIR__ . '/../vendor/autoload.php';
$loader->add($namespace, __DIR__);
$logger = new \Monolog\Logger("Sphring${pluginName}Test");
$logger->pushHandler(new \Monolog\Handler\StreamHandler('php://stdout', \Monolog\Logger::DEBUG));
$loggerSphring = \Arthurh\Sphring\Logger\LoggerSphring::getInstance();
$loggerSphring->setLogger($logger);
$loggerSphring->setWithFile(false);
$loggerSphring->setWithClass(false);
ini_set("variables_order", "EGPCS");
return $loader;