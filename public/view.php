<?php

    require_once('../database.php');

    $id = $_GET['id'] ?? null;

    if(!$id){

        header('location:index.php');
        exit;
    }

    $statement = $pdo->prepare("SELECT * FROM boardGames WHERE id = :id");
    $statement -> bindValue(':id',$id);
    $statement -> execute();
    $boardgame = $statement->fetch(PDO::FETCH_ASSOC);
?>


<?php include_once "../views/partials/header.php" ?>
    <body>
        <div class="main">
        <p>
            <a href="index.php" class="btn btn-secondary">Go Back to Board Game Library</a>
        </p>

        <h1><?php echo $boardgame['name'] ?></h1>

        <?php if ($boardgame['imageURL']){?>
            <img src="<?php echo $boardgame['imageURL']?>" class="update-image">

        <?php } ?>

        <h5 class="mt-4"><strong>Type of Game</strong></h5>
        <p><?php echo $boardgame['baseOrExpansion']; ?></p>
        
        <div class="row">
            <div class="col-md-2">
                <h5 class="mt-4"><strong>Players</strong></h5>
                <p><?php echo $boardgame['minimumPlayers'].'-'.$boardgame['maximumPlayers']; ?></p>
            </div>
            <div class="col-md-2">
                <h5 class="mt-4"><strong>Time</strong></h5>
                <p><?php echo $boardgame['minimumTime'].'-'.$boardgame['maximumTime']; ?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
               <h5 class="mt-4"><strong>Owner</strong></h5>
                <p><?php echo $boardgame['owner']; ?></p>
            </div>
            <div class="col-md-2">
               <h5 class="mt-4"><strong>Library</strong></h5>
                <p><?php echo $boardgame['library']; ?></p>
            </div>
            <div class="col-md-2">
               <h5 class="mt-4"><strong>Location</strong></h5>
                <p><?php echo $boardgame['location']; ?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2">
                <h5 class="mt-4"><strong>Redundant Expansion?</strong></h5>
                <p><?php echo $boardgame['isRedundant']; ?></p>
            </div>
            <div class="col-md-2">
                <h5 class="mt-4"><strong>Played?</strong></h5>
                <p><?php echo $boardgame['hasPlayed']; ?></p>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <h5 class="mt-4"><strong>Description</strong></h5>
                <p><?php echo nl2br($boardgame['description']); ?></p>
            </div>
        </div>
