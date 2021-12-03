<?php

$pdo = new PDO('mysql:host=boardgamedb.crkpfrpnbimw.us-east-2.rds.amazonaws.com;port=3306;dbname=BoardGameLibrary', 'root', 'dragonsFo1!y');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);