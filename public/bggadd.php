<?php

$name = $_GET["name"] ?? null;
$games = [];


$name = htmlspecialchars($name);
$name = str_replace(' ','%20', $name);

if ($name) {

    $searchURL = 'https://www.boardgamegeek.com/xmlapi/search?search=' . $name;
    $resource = curl_init();

    curl_setopt_array($resource, [
        CURLOPT_URL => $searchURL,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => ['content-type: application/xml']
    ]);

    $result = curl_exec($resource);

    $games = new SimpleXMLElement($result);

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
            if (empty($games)) { ?>
                <h3>Could not find games with the title <?php echo $name ?></h3>
            <?php } else {
                echo '<pre>';
                echo var_dump($games);
                echo '</pre>';
            } ?>
        <?php } ?>
    </div>

</body>

</html>