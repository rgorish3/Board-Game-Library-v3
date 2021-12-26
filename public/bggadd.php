<?php

$name = $_GET["name"] ?? null;

$nameReplace = htmlspecialchars($name);
$nameReplace = str_replace(' ', '%20', $nameReplace);

$exact = $_GET["exact"] ?? 0;

if ($name) {

    $gameSearchURL = 'https://www.boardgamegeek.com/xmlapi/search?search=';             //Base URL for BoardGameGeek's search function which allows
                                                                                        //  searching for specific game names. This will provide the 
                                                                                        //  short form of game information including objectid.

    $objectSearchURL = 'https://www.boardgamegeek.com/xmlapi/boardgame/';               //Base URL for BoardGameGeek's specific boardgame lookup. Look up
                                                                                        //  boardgames based on specific objectid. The objectid can be
                                                                                        //  obtained using the search function. The call can accommodate
                                                                                        //  one or multiple comma-delimited objectids. This will return the
                                                                                        //  long form of game information.

    /*
    Using the terms SEARCH and SEARCHED in variables to refer to the 
    data gathered from the search function. Using the terms OBJECT 
    or OBJECTID in variables to refer to the data gathered from the
    boardgame search. Exception is $objectidArray which is an array
    of objectids as gathered in the pull from search.
    */

    // INITIALIZE ARRAY FOR STORING OBJECTIDS
    $objectidArray = [];


    // CONNECT TO GAMESEARCHURL TO SEARCH FOR GAME

    /* 
    Once the XML is pulled, it is translated into simplexml, then translated to json, then
    decoded into an array. I do not know if this is the most efficent way to do this, but
    it was the only way I could find to put XML into an array format.
    */

    $resource = curl_init();

    curl_setopt_array($resource, [
        CURLOPT_URL => $gameSearchURL . $nameReplace . '&exact='. $exact,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['content-type: application/xml']
    ]);

    $result = curl_exec($resource);

    $xml = simplexml_load_string($result);
    $json = json_encode($xml);
    $gameSearchResult = json_decode($json, TRUE);



    // END CONNECT TO SEARCH URL



    $bggSearchedBoardGames=[];                                                                          


    if(!empty($gameSearchResult['boardgame'])){                                                         //If there is only one game, $gameSearchResult['boardgame'] will
                                                                                                        //  be an associative array. If there is more than one game, it will
                                                                                                        //  be an indexed array of associative arrays. 
                                                                                                        //  Using is_int on the first index of array_keys
                                                                                                        //  to determine if it is an associative or indexed array. According to
                                                                                                        //  the format of the returned xml, the array will never have a key of an
                                                                                                        //  integer if it is not an indexed array so this is safe. If it is an
                                                                                                        //  indexed array, set $bggSearchedBoardGames to be equal to it. If it is
                                                                                                        //  an associative array, push the associative array as the first element
                                                                                                        //  of $bggSearchedBoardGames to make it an indexed array of associative
                                                                                                        //  arrays. Check to see if $gameSearchResult['boardgame'] is empty first
                                                                                                        //  as is_int requires a non-empty array to function.
        if(is_int(array_keys($gameSearchResult['boardgame'])[0])){    
            $bggSearchedBoardGames = $gameSearchResult['boardgame'];                  
        }
        else{
            $bggSearchedBoardGames[] = $gameSearchResult['boardgame'];                
        }
    }


    // POPULATE OBJECTIDARRAY

    for ($i = 0; $i < count($bggSearchedBoardGames); $i++) {
        $objectidArray[] = $bggSearchedBoardGames[$i]['@attributes']['objectid'];
    }

    // END POPULATE OBJECTIDARRAY


    // CONNECT TO OBJECTSEARCHURL TO SEARCH FOR OBJECTIDS
    if(!empty($objectidArray)) {                                                                    //Only do this if $objectidArray is not empty because objectSearchURL
                                                                                                    //  404s if it does not have an object to search for.
        curl_setopt_array($resource, [
            CURLOPT_URL => $objectSearchURL . implode(',', $objectidArray),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['content-type: application/xml']
        ]);

        $result = curl_exec($resource);

        $xml = simplexml_load_string($result);
        $json = json_encode($xml);
        $objectSearchResult = json_decode($json, TRUE);
    

    // END CONNECT TO OBJECTSEARCHURL



        if(is_int(array_keys($objectSearchResult['boardgame'])[0])){                                //See above regarding how $bggSearchedBoardGames was set. Setting $bggObjectBoardGames
                                                                                                    //  the same way.
            $bggObjectBoardGames = $objectSearchResult['boardgame'] ?? [];                         
        }
        else
        {
            $bggObjectBoardGames[] = $objectSearchResult['boardgame'] ?? [];
        }
    }

}


?>


<?php include_once("../views/partials/header.php") ?>

<body>
    <div class="main">
          <p>
            <a href="create.php" class="btn btn-secondary">Go Back to Add Game</a>
        </p>

        <h1>Search BoardGameGeek For Game</h1>

        <!--SEARCH BAR-->
        <form>
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <div class="mb-3">
                        <div class="form-group mb-1">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Game Title" value="<?php echo $name ?>">
                        </div>
                        <?php if(!$exact){ ?>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="exact" value='1' id="exact">
                                <label class="form-check-label" for="exact">Exact Text Search</label>
                            </div>
                        <?php }
                        else{ ?>
                            <div class="form-check form-switch ms-1">
                                <input class="form-check-input" type="checkbox" role="switch" name="exact" value='1' id="exact" checked>
                                <label class="form-check-label" for="exact">Exact Text Search</label>
                            </div>

                        <?php } ?>
                    </div>
                </div>
                <div class="col-md-1 col-sm-1">
                    <div class="mb-3">
                        <div class="form-group">
                            <button class="btn btn-secondary" type="submit" id="searchButton">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!--END SEARCH BAR-->

        <?php if ($name) {
            if (empty($bggSearchedBoardGames)) { ?>
                <h4>Could not find games with the title <?php echo $name ?></h4>
                <?php } else {
                for ($i = 0; $i < count($bggSearchedBoardGames); $i++) {
                    $bggURL = $bggObjectBoardGames[$i]['image'] ?? '';
                    $bggName = $bggSearchedBoardGames[$i]['name'] ?? '';
                    $bggYearPublished = $bggSearchedBoardGames[$i]['yearpublished'] ?? '';
                    $bggMinPlayers = $bggObjectBoardGames[$i]['minplayers'] ?? 0;
                    $bggMaxPlayers = $bggObjectBoardGames[$i]['maxplayers'] ?? '';
                ?>
                    <div class="mb-2 border border-1 border-dark">

                        <div class="row mb-1 mt-1 ms-1">
                            <div class="col-md-4">
                                <img src="<?php echo $bggURL ?>" class="update-image">
                            </div>
                            <div class="col-md-6">
                                <p>
                                    <strong><?php echo $bggName ?> </strong> </br>
                                    <?php echo $bggYearPublished . '</br>';
                                    echo $bggMinPlayers . '-' . $bggMaxPlayers . ' players' . '</br>'; ?>
                                </p>
                            </div>
                            <div class="col-md-1">
                                <form style="display: inline-block" method="post" action="create.php">
                                    <input type="hidden" name="name" value="<?php echo $bggSearchedBoardGames[$i]['name']?>">
                                    <input type="hidden" name="minPlayers" value="<?php echo $bggObjectBoardGames[$i]['minplayers'] ?>">
                                    <input type="hidden" name="maxPlayers" value="<?php echo $bggObjectBoardGames[$i]['maxplayers'] ?>">
                                    <input type="hidden" name="minTime" value="<?php echo $bggObjectBoardGames[$i]['minplaytime'] ?>">
                                    <input type="hidden" name="maxTime" value="<?php echo $bggObjectBoardGames[$i]['maxplaytime'] ?>">
                                    <input type="hidden" name="imageURL" value="<?php echo $bggURL ?>">
                                    <input type="hidden" name="description" value="<?php echo strip_tags($bggObjectBoardGames[$i]['description'],'<br>') ?>">
                                    <input type="hidden" name="frombggadd" value="true">
                                    <input type="hidden" name="objectid" value="<?php echo $objectidArray[$i] ?>">
                                    <button type="submit" class="btn btn-lg btn-success mt-1">Select</button>
                                </form>
                            </div>
                        </div>
                    </div>
            <?php }
            } ?>
        <?php } ?>
    </div>

</body>

</html>