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
    
    // Prepare search query
    $search = " SELECT wine.wine_id, 
                       wine.wine_name, 
                       group_concat(grape_variety.variety
                           order by wine_variety.id ASC SEPARATOR ' ') AS wine_variety,
                       wine.year,
                       winery.winery_name,
                       region.region_name,
                       MAX(inventory.cost) AS cost,
                       SUM(inventory.on_hand) AS on_hand
                FROM   wine, wine_variety, grape_variety, winery, region, inventory
                WHERE  wine.wine_id = wine_variety.wine_id
                AND    wine.winery_id = winery.winery_id
                AND    winery.region_id = region.region_id
                AND    wine_variety.variety_id = grape_variety.variety_id
                AND    wine.wine_id = inventory.wine_id ";
    
    // wineNameBind
    if (strcmp($wineName, '%All%') != 0) {
        $search = " AND wine.wine_name
                   LIKE :wineNameBind ";
    }
    
    // wineryNameBind
    if (strcmp($wineryName, '%All%') != 0) {
        $search .= " AND winery.winery_name
                    LIKE :wineryNameBind ";
    }
    
    // Limit to 100 wines
    $search .= " GROUP BY wine.wine_id LIMIT 100";
    
    // Execute query
    try {
        $statement = $dbh->prepare($search);
        
        if(strcmp($wineName, '%All%') != 0) {
            $statement->bindParam(':wineNameBind', $wineName, PDO::PARAM_STR, 50+2);
        }
        
        if(strcmp($wineryName, '%All%') != 0) {
            $statement->bindParam(':wineryNameBind', $wineryName, PDO::PARAM_STR, 100+2);
        }
        
        $statement->execute();
        
        // Debugging
        // echo $statement->debugDumpParams();
    
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        fatalError("PDO Exception occurred");
    }
    
    // Prepare results for URL
    $results = http_build_query(array('r' => $result));
    $resultsURL = "results.php?".$results;
    
    // Close database connection
    $dbh = null;
    
    // TESTING
    //if(DEBUG_MODE) {
    //    echo '<pre>';
    //    echo print_r($result);
    //    echo '</pre>';
    //    phpinfo();
    //    fatalError("Query: {$search}");
    //}
    
    // Redirect to Results page
    header("Location: {$resultsURL}");
    exit();
?>