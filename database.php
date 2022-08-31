<?php

require_once("vendor/autoload.php");
$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$pdo = new PDO('mysql:host='.getenv('DB_ADDRESS'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);