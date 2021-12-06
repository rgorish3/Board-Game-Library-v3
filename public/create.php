<?php

require_once("../database.php");
require_once("../functions.php");

$errors = [];

$name = '';
$baseOrExp = '';
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


if($_SERVER['REQUEST_METHOD'] === 'POST'){

    require_once('../validate.php');


    if(empty($errors)){

        $statement = $pdo->prepare("INSERT INTO boardGames (name, baseOrExpansion, minimumPlayers, maximumPlayers, 
            minimumTime, maximumTime, location, owner, description, isRedundant, library, has played, imageURL)
            VALUES(:name, :baseOrExp, :minPlayers, :maxPlayers, :minTime, :maxTime, :location, :owner, :description,
            :redundant, :library, :played, :date)");

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
        $statement -> bindValue(':date',$date('Y-m-d H:i:s'));


        $statement->execute();

        header('Location: index.php');


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

        <?php include_once "../views/partials/form.php" ?>
    </div> 
</body>

</html>