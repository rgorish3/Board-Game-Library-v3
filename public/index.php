<?php

//NOTES: 
//INTERMITTENT ERROR WITH DELETING. CONFIRM DIALOG BOX NOT POPPING UP ON NEWER ENTRIES TO TABLE ADDED THROUGH INTERFACE.
//  NOT SURE WHAT HAPPENS WHEN ADDED THROUGH QUERY ANALYZER. 


require_once("../database.php");

$numPlayers = $_GET["numPlayers"] ?? '';
$time= $_GET["time"] ?? '';
$search= $_GET["search"] ?? '';
$search_WithWildcards='%'.$search.'%';
$redundant= $_GET["redundant"] ?? '';

$timeMode = $_GET["timeMode"] ?? '';
$libraryPassed = $_GET["library"] ?? [];

$positionalTotal = 0;
$positionalCounter = 1;                                                 /*  Using positional parameters for binding values to SQL query. Because we do not know
                                                                            how many of the filter options the user will choose at any given time, we determine
                                                                            the total by incrementing $positionalTotal each time a segment is added to the query
                                                                            based on how many positional parmeters are used. Then as the values are bound to the
                                                                            positions, we increment $positionalCounter to denote which position we are binding
                                                                            next.
                                                                        */



//BASE STRING FOR QUERY

$queryStr = 'SELECT * FROM boardGames WHERE 1=1 ';                      /* 'WHERE 1=1' is included to allow the WHERE clause to be present first in the query
                                                                            as there is no way to know which if any of the later filtering options the user
                                                                            use.
                                                                        */


//PLAYERS SEARCH

if($numPlayers)
{
    //$queryStr.='AND :numPlayers1 >= minimumPlayers AND :numPlayers2 <= maximumPlayers ';

    $queryStr.='AND ? >= minimumPlayers AND ? <= maximumPlayers ';

    $positionalTotal += 2;
}


//TIME SEARCH
if($time){
    if($timeMode === 'approx'){
        
        //$queryStr.='AND :time1 >= minimumTime AND :time2 <= maximumTime ';
        
        $queryStr.='AND ? >= minimumTime AND ? <= maximumTime ';
        
        $positionalTotal += 2;
    }
    else{
        //$queryStr.='AND :time1 >= maximumTime ';
        
        $queryStr.='AND ? >= maximumTime ';

        $positionalTotal++;
    
    }
}

//REUDNDANT SEARCH

if(!$redundant){
    $queryStr.="AND isRedundant = 'N' ";
}

//NAME SEARCH

if($search){
    //$queryStr.="AND name LIKE :search ";
    $queryStr.="AND name LIKE ? ";
    $positionalTotal++;
}


//LIBRARY SEARCH

$librarySearchStr = '';
$count = 0;
$placeholders='';
                                                                             /*  Count is the length of $libraryPassed, $placeholders is an string of question marks
                                                                                delimited by commas. One question mark for each item in $libraryPassed. 
                                                                            */


if(!empty($libraryPassed)){
    

    //$librarySearchStr = implode(',', $libraryPassed);

    $count = count($libraryPassed);
    $placeholders = implode(',', array_fill(0, $count, '?'));


    $queryStr.="AND library IN ($placeholders) ";

    $positionalTotal += $count;
}



//APPEND ORDERING

$queryStr .= 'ORDER BY name';



//QUERY FOR POPULATING TABLE
$statement = $pdo->prepare($queryStr);

if($numPlayers){
    // $statement->bindValue(':numPlayers1',$numPlayers);
    // $statement->bindValue(':numPlayers2',$numPlayers);

    $statement->bindValue($positionalCounter, $numPlayers);
    $positionalCounter++;

    $statement->bindValue($positionalCounter,$numPlayers);
    $positionalCounter++;
}
if($time){
    if($timeMode === 'approx'){
        // $statement->bindValue(':time1',$time);
        // $statement->bindValue(':time2',$time);

        $statement->bindValue($positionalCounter, $time);
        $positionalCounter++;

        $statement->bindValue($positionalCounter, $time);
        $positionalCounter++;

    }
    else{
        // $statement->bindValue(':time1',$time);

        $statement->bindValue($positionalCounter, $time);
        $positionalCounter++;
    }
}
if($search){
    // $statement->bindValue(':search',$search_WithWildcards);

    $statement->bindValue($positionalCounter,$search_WithWildcards);
    $positionalCounter++;
}

if(!empty($libraryPassed)){
    for($i=0; $positionalCounter<=$positionalTotal ; $i++){
        $statement->bindValue($positionalCounter,$libraryPassed[$i]);
        $positionalCounter++;
    }
}

$statement->execute();


//FETCH ARRAY OF BOARDGAMES GATHERED BY QUERY

$boardgames = $statement->fetchAll(PDO::FETCH_ASSOC);



//QUERY FOR POPULATING LIBRARIES
$statement = $pdo->prepare('SELECT distinct Library FROM boardGames ORDER BY Library');
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
                               
                            <?php if(in_array($library['Library'], $libraryPassed, $strict=false) || empty($_GET)){ ?>  <!-- Checking to see whether the library is was passed in GET. If it
                                                                                                                            was, displays checkbox as checked. 
                                                                                                                            
                                                                                                                            Will also check to see if $_GET is empty and will use checked version 
                                                                                                                            for that as well. This is intended to have all libraries selected on 
                                                                                                                            initial page load as n$_GET should be empty without a query string.
                                                                                                                            However, I am a little concerned this may be dirty. Testing is required.
                                                                                                                        -->
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
                                <button type="submit" class="btn btn-sm btn-danger" onClick="return confirm('Are you sure you want to delete <?php echo $boardgame['name'];?>?')">Delete</button>
                            </form>
                        </td>
                        
                    </tr>


                <?php endforeach;?>    


            </tbody>

    


        </div>
</body>
</html>