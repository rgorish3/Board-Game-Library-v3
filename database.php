<?php

require_once("vendor/autoload.php");
$dotenv = \Dotenv\Dotenv::createUnsafeImmutable(__DIR__);
$dotenv->load();

$pdo = new PDO('mysql:host='.getenv('DB_ADDRESS'), getenv('DB_USERNAME'), getenv('DB_PASSWORD'));
//$pdo = new PDO('mysql:host=boardgamedb.crkpfrpnbimw.us-east-2.rds.amazonaws.com;port=3306;dbname=BoardGameLibrary', 'root', 'dragonsFo1!y');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);