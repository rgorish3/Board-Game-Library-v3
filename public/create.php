<?php

require_once("../database.php");
require_once("../functions.php");


$errors = [];

$name = '';
$baseOrExp = '';
$baseOrExp_base = '';
$baseOrExp_exp = '';
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
$objectid = null;
$url = $_POST['imageURL'] ?? '';
$frombggadd = $_POST['frombggadd'] ?? null;


$boardgame = ['imageURL' => $url];


if($_SERVER['REQUEST_METHOD'] === 'POST'){

    if($frombggadd){

        $name = $_POST['name'];
        $minPlayers = $_POST['minPlayers'];
        $maxPlayers = $_POST['maxPlayers'];
        $minTime = $_POST['minTime'];
        $maxTime = $_POST['maxTime'];
        $description = str_replace("<br/>","\n",$_POST['description']);
        $objectid = $_POST['objectid'];



        if($minTime === $maxTime){
            if($minTime<=10){
                $mintime=1;
                $maxTime=10;
            }
            else if($minTime<=20){
                $minTime-=5;
                $maxTime+=5;
            }
            else if($minTime<=30){
                $minTime-=10;
                $maxTime+=10;
            }
            else if($minTime<=60){
                $minTime-=15;
                $maxTime+=15;
            }
            else if($minTime<=90){
                $minTime-=20;
                $maxTime+=20;
            }
            else if($minTime<=150){
                $minTime-=30;
                $maxTime+=30;
            }
            else{
                $minTime-=40;
                $maxTime+=40;
            }
        }


    }
    else{
        require_once('../validate.php');


        if(empty($errors)){

            $statement = $pdo->prepare("INSERT INTO boardGames (name, baseOrExpansion, minimumPlayers, maximumPlayers, 
                minimumTime, maximumTime, location, owner, description, isRedundant, library, hasPlayed, imageURL, createDate, bggObjectID)
                VALUES(:name, :baseOrExp, :minPlayers, :maxPlayers, :minTime, :maxTime, :location, :owner, :description,
                :redundant, :library, :played, :imageURL, :date, :objectid)");

            $statement -> bindValue(':name',$name);
            $statement -> bindValue(':baseOrExp',$baseOrExp);
            $statement -> bindValue(':minPlayers',$minPlayers);
            $statement -> bindValue(':maxPlayers',$maxPlayers);
            $statement -> bindValue(':minTime',$minTime);
            $statement -> bindValue(':maxTime',$maxTime);
            $statement -> bindValue(':location',$location);
            $statement -> bindValue(':owner',$owner);
            $statement -> bindValue(':description',$description);
            $statement -> bindValue(':redundant',$redundant);
            $statement -> bindValue(':library',$library);
            $statement -> bindValue(':played',$played);
            $statement -> bindValue('imageURL',$imagePath);
            $statement -> bindValue(':date',date('Y-m-d H:i:s'));
            $statement -> bindValue(':objectid',$objectid);


            $statement->execute();

            header('Location: index.php');

        }
    }
}
?>

<?php include_once "../views/partials/header.php"?>
<body>
    <div class="main">
        <p>
            <a href="index.php" class="btn btn-secondary">Go Back to Board Game Library</a>
        </p>

        <h1>Create New Game</h1>

        <p> 
            <a href="bggadd.php" class="btn btn-success">Search BoardGameGeek for Game</a>
        </p>

        <?php include_once "../views/partials/form.php" ?>
    </div> 
</body>

</html>