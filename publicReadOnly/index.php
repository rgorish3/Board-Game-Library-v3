<?php


require_once('../search.php');

?>



<?php include_once("../views/partials/header.php");?>


<body>
    <div class="main">
        <h1>Board Game Library</h1> 


        <!--RESET BUTTON-->

        <p>
            <a href="index.php" class="btn btn-secondary">Reset</a>
        </p>


        <!-- ADD Search Form. -->

        <?php include_once('../views/partials/search_form.php'); ?>
        

        <!--DISPLAY TABLE FOR DISPLAYING THE BOARD GAMES-->

        <table class="table">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">Base/Expansion</th>
                    <th scope="col">Min Players</th>
                    <th scope="col">Max Players</th>
                    <th scope="col">Min Time</th>
                    <th scope="col">Max Time</th>
                    <th scope="col">Library</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($boardgames as $i => $boardgame) : ?>
                    <tr>
                        <th scope="row"><?php echo $i+1; ?></th>

                        <td>  
                            
                            <img src="<?php echo $boardgame['imageURL']; ?>" class="thumb-image">       <!--Works with Symbolic Link to images file in public-->
                            

                        </td>

                        <td><?php echo $boardgame['name']; ?></td>
                        <td><?php echo $boardgame['baseOrExpansion']; ?></td>
                        <td><?php echo $boardgame['minimumPlayers']; ?></td>
                        <td><?php echo $boardgame['maximumPlayers']; ?></td>
                        <td><?php echo $boardgame['minimumTime']; ?></td>
                        <td><?php echo $boardgame['maximumTime']; ?></td>
                        <td><?php echo $boardgame['library']; ?></td>
                    

                        <!--DEFINE THE ACTION BUTTONS-->
                        
                        <td>
                            
                            <a href="view.php?id=<?php echo $boardgame['id'] ?>" type="button" class="btn btn-sm btn-info mb-2">View</a>
                        
                          
                        </td>
                        
                    </tr>


                <?php endforeach;?>    


            </tbody>

    


    </div>


</body>
</html>