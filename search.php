<?php

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

