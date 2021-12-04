<?php


$name = $_POST['name'];
$baseOrExp = $_POST['baseOrExp'];
$minPlayers = $_POST['minPlayers'];
$maxPlayers = $_POST['maxPlayers'];
$minTime = $_POST['minTime'];
$maxTime = $_POST['maxTime'];
$owner = $_POST['owner'];
$redundant = $_POST['redundant'];
$library = $_POST['library'];

$image_path = '';

if(!$name){
    $errors[] = 'Name is required';
}

if(!$price){
     $errors[] = 'Designating base and/or expansion is required';
}

if(!$minPlayers){
    $errors[] = 'Minimum Players is required';
}
if(!$maxPlayers){
    $errors[] = 'Maximum Players is required';
}

if(!$minTime){
    $errors[] = 'Minimum Time is required';
}

if(!$maxTime){
    $errors[] = 'Maximum Time is required';
}

if(!$owner){
    $errors[] = 'Owner is required';
}

if(!$redundant){
    $errors[] = 'Designating Redundant Expansion is required';
}

if(!$library){
    $errors[] = 'Library is required';
}



if(!is_dir(__DIR__.'/public/images')){
     mkdir(__DIR__.'/public/images');
}


if(empty($errors)){

    $image = $_FILES['image'] ?? null;
    $imagePath = $boardgame['image'];


    if($image['name'] != '' && $image['tmp_name']){
        
        if($boardgame['image']){
            unlink(__DIR__.'/public/'.$boardgame['image']);  //unlink deletes the image
        }   
        $imagePath = 'images/'.randomString(8).'/'.$image['name'];
        mkdir(dirname(__DIR__.'/public/'.$imagePath));
        move_uploaded_file($image['tmp_name'], __DIR__.'/public/'.$imagePath);
    }
}