<?php

require_once("../database.php");
require_once("../functions.php");

$errors = [];

$name = '';
$baseOrExpansion = '';
$minPlayers = '';
$maxPlayers = '';
$minTime = '';
$maxTime = '';
$location= '';
$owner = '';
$description ='';
$redundant = '';
$library = '';
$played = '';


$boardgame = ['image' => ''];        /*Declaring boardgame variable because
                                    it is being ported over from 
                                    forms.php as it is used in update.php.
                                    */


