<?php 

    require_once("../database.php");
    require_once("../functions.php");

    $id = $_GET['id'] ?? null;

    if(!$id){
        header('location: index.php');
        
        exit;
    }


    $statement = $pdo->prepare("SELECT * FROM boardGames WHERE id = :id");
    $statement -> bindValue(':id',$id);
    $statement -> execute();
    $boardgame = $statement->fetch(PDO::FETCH_ASSOC);

   
    $errors = [];



    $name = $boardgame['name'];
    $baseOrExp = $boardgame['baseOrExpansion'];
    $minPlayers = $boardgame['minimumPlayers'];
    $maxPlayers = $boardgame['maximumPlayers'];
    $minTime = $boardgame['minimumTime'];
    $maxTime = $boardgame['maximumTime'];
    $location = $boardgame['location'];
    $owner = $boardgame['owner'];
    $description = $boardgame['description'];
    $redundant = $boardgame['isRedundant'];
    $library = $boardgame['library'];
    $played = $boardgame['hasPlayed'];
    $baseOrExp_base='';
    $baseOrExp_exp='';


    if($baseOrExp === 'Base'){
        $baseOrExp_base = 'on';    
    }
    elseif($baseOrExp === 'Expansion'){
        $baseOrExp_exp = 'on';
    }
    elseif($baseOrExp ==='Base and Expansion')
    {
        $baseOrExp_base = 'on';
        $baseOrExp_exp = 'on';
    }

    if($redundant != 'Y'){

        $redundant = '';
    }

    if($played != 'Y')
    {
        $played = '';
    }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    require_once("../validate.php");

    if (empty($errors)) {


        $statement = $pdo->prepare("UPDATE boardGames SET name=:name, baseOrExpansion=:baseOrExp,
            minimumPlayers=:minPlayers, maximumPlayers=:maxPlayers, minimumTime=:minTime, maximumTime=:maxTime,
            location=:location, owner=:owner, description=:description, isRedundant=:redundant, library=:library,
            hasPlayed=:played, imageURL=:imageURL 
            WHERE id=:id");

        $statement->bindValue(':id', $id);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':baseOrExp', $baseOrExp);
        $statement->bindValue(':minPlayers',$minPlayers);
        $statement->bindValue(':maxPlayers', $maxPlayers);
        $statement->bindValue(':minTime',$minTime);
        $statement->bindValue(':maxTime',$maxTime);
        $statement->bindValue(':location',$location);
        $statement->bindValue(':owner', $owner);
        $statement->bindValue(':description',$description);
        $statement->bindValue(':redundant', $redundant);
        $statement->bindValue(':library', $library);
        $statement->bindValue(':played',$played);
        $statement->bindValue(':imageURL',$imagePath);


        $statement->execute();

        header('Location: index.php');
    }
}


?>

<?php include_once "../views/partials/header.php" ?>
<body>
    <div class="main">
        <p>
            <a href="index.php" class="btn btn-secondary">Go Back to Board Game Library</a>
        </p>
        
        <h1>Update <strong><?php echo $boardgame["name"] ?></strong></h1>


        <?php include_once "../views/partials/form.php" ?>
    </div>



</body>

</html>