<?php

$name = $_GET["name"] ?? null;

$name = htmlspecialchars($name);
$name = str_replace(' ','%20', $name);

if ($name) {

    $gameSearchURL = 'https://www.boardgamegeek.com/xmlapi/search?search=' ;            //Base URL for BoardGameGeek's search function which allows
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
        CURLOPT_URL => $gameSearchURL. $name,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['content-type: application/xml']
    ]);

    $result = curl_exec($resource);

    $xml = simplexml_load_string($result);
    $json = json_encode($xml);
    $gameSearchResult = json_decode($json, TRUE);

    // END CONNECT TO SEARCH URL
    
    

    $bggSearchedBoardGames = $gameSearchResult['boardgame'] ?? [];                  //Set to the inner 'boardgame' array for easier access


    // POPULATE OBJECTIDARRAY

    for($i=0; $i< count($bggSearchedBoardGames);$i++ ){
        $objectidArray[] = $gameSearchResult['boardgame'][$i]['@attributes']['objectid'];
    }

    // END POPULATE OBJECTIDARRAY


    // CONNECT TO OBJECTSEARCHURL TO SEARCH FOR OBJECTIDS

    curl_setopt_array($resource, [
        CURLOPT_URL => $objectSearchURL. implode(',',$objectidArray),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['content-type: application/xml']
    ]);

    $result = curl_exec($resource);

    $xml = simplexml_load_string($result);
    $json = json_encode($xml);
    $objectSearchResult = json_decode($json, TRUE);

    // END CONNECT TO OBJECTSEARCHURL

    $bggObjectBoardGames = $objectSearchResult['boardgame'] ?? [];                  //Set to the inner 'boardgame array for easier access

    

}


?>


<?php include_once("../views/partials/header.php") ?>

<body>
    <div class="main">


        <h1>Search BoardGameGeek For Game</h1>

        <!--SEARCH BAR-->
        <form>
            <div class="row">
                <div class="col-md-4 col-sm-4">
                    <div class="mb-3">
                        <div class="form-group">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Game Title" value="<?php echo $name ?>">
                        </div>
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
                for($i=0;$i<count($bggSearchedBoardGames);$i++){
                    
                    ?>
                    <img src="<?php echo $bggObjectBoardGames[$i]['thumbnail']?>" class="update-image">
                    
                    <p>
                        <strong><?php echo $bggSearchedBoardGames[$i]['name'] ?> </strong> </br>
                        <?php echo $bggSearchedBoardGames[$i]['yearpublished'].'</br>';
                        echo $bggObjectBoardGames[$i]['minplayers'].'-'.$bggObjectBoardGames[$i]['maxplayers'].' players'.'</br>';?>
                        <hr/>
                <?php }
            } ?>
        <?php } ?>
    </div>

</body>

</html>