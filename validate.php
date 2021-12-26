<?php


$name = $_POST['name'];
$baseOrExp_base = $_POST['baseOrExp_base'] ?? '';
$baseOrExp_exp = $_POST['baseOrExp_exp'] ?? '';
$minPlayers = $_POST['minPlayers'];
$maxPlayers = $_POST['maxPlayers'];
$minTime = $_POST['minTime'];
$maxTime = $_POST['maxTime'];
$location = $_POST['location'];
$owner = $_POST['owner'];
$description = $_POST['description'];
$redundant = $_POST['redundant'] ?? '';
$library = $_POST['library'];
$played = $_POST['played'] ?? '';
$objectid = $_POST['objectid'] ?? null;
$image_path =  '';





if(!$name){
    $errors[] = 'Name is required';
}

if(!$baseOrExp_base && !$baseOrExp_exp){
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

if(!$library){
    $errors[] = 'Library is required';
}

if($maxTime < $minTime){
    $errors[] = 'Minimum Time must be less than or equal to Maximum Time';
}

if($maxPlayers < $minPlayers){
    $errors[] = 'Minimum Players must be less than or equal to Maximum Players';
}



if(!is_dir(__DIR__.'/public/images')){
     mkdir(__DIR__.'/public/images');
}



if(empty($errors)){

    $image = $_FILES['image'] ?? null;
    $imagePath = $boardgame['imageURL'];

    if($image['name'] != '' && $image['tmp_name']){
        
        if($boardgame['imageURL']){
            unlink(__DIR__.'/public/'.$boardgame['imageURL']);  //unlink deletes the image
        }   
        $imagePath = 'images/'.randomString(8).'/'.$image['name'];
        mkdir(dirname(__DIR__.'/public/'.$imagePath));
        move_uploaded_file($image['tmp_name'], __DIR__.'/public/'.$imagePath);
    }


    //PREPARE $redundant WITH TEXT TO LOAD INTO DATABASE 
    if($redundant){
        $redundant = 'Yes';
    }
    else{
        $redundant = 'No';
    }
    
    //PREPARE $played WITH TEXT TO LOAD INTO DATABASE 
    if($played){
        $played = 'Yes';
    }
    else{
        $played = 'No';
    }

    //PREPARE $baseOrExp WITH TEXT TO LOAD INTO DATABASE 
    
    if($baseOrExp_base && !$baseOrExp_exp)
    {
        $baseOrExp = 'Base';
    }
    elseif(!$baseOrExp_base && $baseOrExp_exp)
    {
        $baseOrExp = 'Expansion';
    }
    else{
        $baseOrExp = 'Base and Expansion';
    }
    


}