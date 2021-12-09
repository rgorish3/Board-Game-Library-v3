<?php

require_once("../database.php");

$numPlayers = $_GET["numPlayers"] ?? '';
$time= $_GET["time"] ?? '';
$search= $_GET["search"] ?? '';
$search_WithWildcards='%'.$search.'%';
$redundant= $_GET["redundant"] ?? '';

$timeMode = $_GET["timeMode"] ?? '';
$libraryPassed = $_GET["library"] ?? [];


//BASE STRING FOR QUERY

$queryStr = 'SELECT * FROM boardGames WHERE 1=1 ';


//PLAYERS SEARCH

if($numPlayers)
{
    $queryStr.='AND :numPlayers1 >= minimumPlayers AND :numPlayers2 <= maximumPlayers ';
}


//TIME SEARCH
if($time){
    if($timeMode === 'approx'){
        
        $queryStr.='AND :time1 >= minimumTime AND :time2 <= maximumTime ';
    }
    else{
        $queryStr.='AND :time1 >= maximumTime ';
    }
}

//REUDNDANT SEARCH

if(!$redundant){
    $queryStr.="AND isRedundant = 'N' ";
}

//NAME SEARCH

if($search){
    $queryStr.="AND name LIKE :search ";
}

//APPEND ORDERING

$queryStr .= 'ORDER BY name';


//QUERY FOR POPULATING TABLE
$statement = $pdo->prepare($queryStr);

if($numPlayers){
    $statement->bindValue(':numPlayers1',$numPlayers);
    $statement->bindValue(':numPlayers2',$numPlayers);
}
if($time){
    if($timeMode === 'approx'){
        $statement->bindValue(':time1',$time);
        $statement->bindValue(':time2',$time);
    }
    else{
        $statement->bindValue(':time1',$time);
    }
}
if($search){
    $statement->bindValue(':search',$search_WithWildcards);
}

$statement->execute();

// echo '<pre>';
// var_dump( $statement );
// echo '</pre>';


//FETCH ARRAY OF BOARDGAMES GATHERED BY QUERY

$boardgames = $statement->fetchAll(PDO::FETCH_ASSOC);


//QUERY FOR POPULATING LIBRARIES
$statement = $pdo->prepare('SELECT distinct Library FROM boardGames');
$statement->execute();


//FETCH ARRAY OF LIBRARIES GATHERED BY QUERY

$libraries = $statement->fetchAll(PDO::FETCH_ASSOC);



?>



<?php include_once("../views/partials/header.php");?>


<body>
    <div class="main">
        <h1>Board Game Library</h1> 


        <!--ADD BUTTON. LINKS TO ADD GAME PAGE-->

        <p>
            <a href="create.php" class="btn btn-success">Add Game</a>
        </p>


        <!--SEARCH GROUP FOR FILTERING GAMES-->
        <div>
            <form>
                
                <!--LEFT SIDE-->

                <div class="row">
                    <div class="col-md-6">
                        <div >
                            
                            <div class="mb-3">
                                <div class="form-group">
                                    <input type="Number" step="1" class="form-control" placeholder="Number of Players" name="numPlayers" value="<?php echo $numPlayers; ?>">
                                </div>
                            </div>

                            <!--TIME MODE - Approximate time is within min or max time. Max time means max time or under. Without input, 
                                default state is Approximate. Otherwise, wage will select whatever was selected when the search button was clicked.
                            -->
                            <div class="mb-3">
                                <div class="form-group">
                                    <?php if($timeMode === 'max'){ ?>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="timeMode" id="approx" value="approx">
                                            <label class="form-check-label" for="approx">
                                                Approximate Time
                                            </label>
                                            
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="timeMode" id="max" value="max" checked>
                                            <label class="form-check-label" for="max">
                                                Maximum Time
                                            </label>
                                        </div>
                                    <?php }
                                    else{?>
                                    
                                    <div class="form-check">
                                            <input class="form-check-input" type="radio" name="timeMode" id="approx" value="approx" checked>
                                            <label class="form-check-label" for="approx">
                                                Approximate Time
                                            </label>
                                            
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="timeMode" id="max" value="max" >
                                            <label class="form-check-label" for="max">
                                                Maximum Time
                                            </label>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <!--END TIME MODE-->

                            <div class="mb-3">
                                <div class="form-group">  
                                    <input type="Number" step="1" class="form-control" placeholder="Time (Minutes)" name="time" value="<?php echo $time; ?>">
                                </div>
                            </div>

                            <!--REDUNDANT EPANSIONS-->
                            <div class="mb-3">
                                <?php if($redundant === "on"){ ?>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="redundant" id="redundant" checked>
                                        <label class="form-check-label" for="redundant">Redundant Expansions</label>
                                    </div>

                                <?php }
                                else{ ?>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" name="redundant" id="redundant">
                                        <label class="form-check-label" for="redundant">Redundant Expansions</label>
                                    </div>
                                <?php } ?>
                            </div>

                            <!--END REDUNDANT EXPANSIONS-->

                            <div class="mb-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Search" name="search" value="<?php echo $search; ?>">
                                </div>
                            </div>

                            
                            <div class="mb-3">
                                <div class="form-group">
                                    <button class="btn btn-outline-secondary" type="submit" id="button-addon2">Search</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--END LEFT SIDE-->

                    <!--SPACER-->
                    <div class ="col-md-1"></div>
                    <!--END SPACER-->
                    
                    
                    <!--RIGHT SIDE-->
                    
                   
                    <div class="col-md-5">

                        <h3>Libraries</h3>
                        
                        <?php foreach($libraries as $i => $library) : ?>
                               
                            <?php if(in_array($library['Library'], $libraryPassed, $strict=false)){ ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="library[]" value="<?php echo $library['Library']?>"  id="<?php echo $library['Library']?>" checked>
                                    <label class="form-check-label" for="<?php echo $library['Library']?>">
                                        <?php echo $library['Library']; ?>
                                    </label>
                                </div>
                            <?php }
                            else{ ?>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="library[]" value="<?php echo $library['Library']?>"  id="<?php echo $library['Library']?>">
                                    <label class="form-check-label" for="<?php echo $library['Library']?>">
                                        <?php echo $library['Library']; ?>
                                    </label>
                                </div>
                        
                            <?php } ?>
                        
                        <?php endforeach;?>

                    </div>

                    <!--END RIGHT SIDE-->

                </div>
            </form>
        </div>

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
                            <img src="/<?php echo $boardgame['imageURL']; ?>" class="thumb-image">
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
                            
                            <a href="view.php?=<?php echo $boardgame['id'] ?>" type="button" class="btn btn-sm btn-info">View</a>
                            <a href="update.php?=<?php echo $boardgame['id'] ?>" type="button" class="btn btn-sm btn-primary">Edit</a>
                        
                            <!--Deletions should done through Post, not Get, so  using a 
                                form instead of an anchor tag to pass hidden information
                                to be used in a post request-->
        
                            <form style="display: inline-block" method="post" action="delete.php">
                                <input type="hidden" name="id" value="<?php echo $boardgame['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                        
                    </tr>


                <?php endforeach;?>    


            </tbody>

    


        </div>
</body>
</html>