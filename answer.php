<?php
    // Only search.php should access this page
    if (basename($_SERVER["HTTP_REFERER"]) != "search.php") {
        header("Location: search.php");
        exit;
    }
    
    require_once('common.php');
    
    // Establish database connection
    require_once('db.php');
    try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PW);
    }
    catch (PDOException $e) {
        fatalError("Could not connect to database");
    }
    
    // Validate input
    // BUG FIX: Adding percentages based on code example by utrandafirc@yahoo.com at http://php.net/manual/en/pdostatement.bindparam.php
    $wineName = "%".sanitise($_GET["wineName"])."%";
    $wineryName = "%".sanitise($_GET["wineryName"])."%";
    $regionID = sanitise($_GET["region"]);
    $grapeVariety = sanitise($_GET["grape_variety"]);
    $minYear = sanitise($_GET["minYear"]);
    $maxYear = sanitise($_GET["maxYear"]);
    $minStock = sanitise($_GET["minStock"]);
    $minOrder = sanitise($_GET["minOrder"]);
    $minCost = sanitise($_GET["minCost"]);
    $maxCost = sanitise($_GET["maxCost"]);
    
    if(strcmp($wineName, '%All%') == 0) {
        // Prepare all query
        $search = "SELECT * 
                   FROM   wine
                   WHERE  wine.wine_id = * ";
    } else {
        // Prepare specific query
        $search = "SELECT * 
                   FROM   wine
                   WHERE  wine.wine_name
                   LIKE   :wineNameBind ";
    }
    
    if (!is_null($wineryName)) {
        $search .= "AND  winery.winery_name
                    LIKE :wineryNameBind ";
    }
    
    // TESTING
    if(DEBUG_MODE) {
        echo $search;
    }
    
    // Execute query
    try {
        $statement = $dbh->prepare($search);
        
        if(strcmp($wineName, '%All%') != 0) {
            $statement->bindParam(':wineNameBind', $wineName, PDO::PARAM_STR, 50+2);
        }
        
        if(!is_null($wineryName)) {
            $statement->bindParam(':wineryNameBind', $wineryName, PDO::PARAM_STR, 100+2);
        }
        
        $statement->execute();
        
        // Debugging
        echo $statement->debugDumpParams();
    
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        fatalError("PDO Exception occurred");
    }
    
    // Prepare results for URL
    $results = http_build_query(array('r' => $result));
    $resultsURL = "results.php?".$results;
    
    // Close database connection
    $dbh = null;
    
    // Redirect to Results page
    header("Location: {$resultsURL}");
    exit();
?>