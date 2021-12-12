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
            <img src="/<?php echo $boardgame['imageURL']?>" class="update-image">

        <?php } ?>

        