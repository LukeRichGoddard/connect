<?php
    // Only search.php should access this page
    if (basename($_SERVER["HTTP_REFERER"]) != "search.php") {
        header("Location: search.php");
        exit;
    }
    
    require_once('common.php');
    
    // Build checksum
    $queryChecksum = md5(serialize($wineName,$wineryName,$regionID,$grapeVariety,$minYear,$maxYear,$minStock,$minOrder,$minCost,$maxCost));
    
    
    
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
                       group_concat(distinct grape_variety.variety
                           order by wine_variety.id ASC SEPARATOR ' ') AS wine_variety,
                       wine.year,
                       winery.winery_name,
                       region.region_name,
                       MAX(inventory.cost) AS cost,
                       SUM(inventory.on_hand) AS on_hand,
                       FLOOR(SUM(items.qty)/COUNT(distinct grape_variety.variety)) AS total_sold,
                       TRUNCATE(SUM(items.price)/COUNT(distinct grape_variety.variety),2) AS total_revenue
                FROM   wine, wine_variety, grape_variety, winery, region, inventory, items
                WHERE  wine.wine_id = wine_variety.wine_id
                AND    wine.winery_id = winery.winery_id
                AND    winery.region_id = region.region_id
                AND    wine_variety.variety_id = grape_variety.variety_id
                AND    wine.wine_id = inventory.wine_id
                AND    inventory.wine_id = items.wine_id ";
    
    // wineNameBind
    if (strcmp($wineName, '%All%') != 0) {
        $search .= " 
                AND wine.wine_name LIKE :wineNameBind ";
    }
    
    // wineryNameBind
    if (strcmp($wineryName, '%All%') != 0) {
        $search .= " 
                AND winery.winery_name LIKE :wineryNameBind ";
    }
    
    // regionBind
    if ($regionID != 1) {
        $search .= " 
                AND region.region_id = :regionBind ";
    }
    
    // grapeVariety
    if ($grapeVariety != 0) {
        $search .= " 
                AND grape_variety.variety_id = :grapeVarietyBind ";
    }
    
    // minYear
    if ($minYear != 0 or is_null($minYear)) {
        $search .= " 
                AND wine.year >= :minYearBind ";
    }
    
    // maxYear
    if ($maxYear != 0 or is_null($maxYear)) {
        $search .= " 
                AND wine.year <= :maxYearBind ";
    }
    
    // minCost
    if ($minCost != 0 or is_null($minCost)) {
        $search .= " 
                AND inventory.cost >= :minCostBind ";
    }
    
    // maxCost
    if ($maxCost != 0 or is_null($maxCost)) {
        $search .= " 
                AND inventory.cost <= :maxCostBind ";
    }
    
    // GROUP BY
    $search .= " 
                GROUP BY wine.wine_id";
    
    // minOrder
    if ($minOrder != 0 or is_null($minOrder)) {
        $needsAnd = true;
        $search .= " 
                HAVING total_sold >= :minOrderBind ";
    }
    
    // minStock
    if ($minStock != 0 or is_null($minStock)) {
        if($needsAnd) {
            $search .= " 
                AND on_hand >= :minStockBind ";
        } else {
            $search .= " 
                HAVING on_hand >= :minStockBind ";
        }
    }
    
    // Order
    $search .= " 
                ORDER BY wine.wine_id";
    
    // Execute query
    try {
        $statement = $dbh->prepare($search);
        
        if(strcmp($wineName, '%All%') != 0) {
            $statement->bindParam(':wineNameBind', $wineName, PDO::PARAM_STR, 50+2);
        }
        
        if(strcmp($wineryName, '%All%') != 0) {
            $statement->bindParam(':wineryNameBind', $wineryName, PDO::PARAM_STR, 100+2);
        }
        
        if ($regionID != 1) {
             $statement->bindParam(':regionBind', $regionID, PDO::PARAM_INT);
        }
        
        if ($grapeVariety != 0) {
            $statement->bindParam(':grapeVarietyBind', $grapeVariety, PDO::PARAM_INT);
        }
    
        if ($minYear != 0 or is_null($minYear)) {
            $statement->bindParam(':minYearBind', $minYear, PDO::PARAM_INT);
        }
        
        if ($maxYear != 0 or is_null($maxYear)) {
            $statement->bindParam(':maxYearBind', $maxYear, PDO::PARAM_INT);
        }
        
        if ($minOrder != 0 or is_null($minOrder)) {
            $statement->bindParam(':minOrderBind', $minOrder, PDO::PARAM_INT);
        }
        
        if ($minStock != 0 or is_null($minStock)) {
            $statement->bindParam(':minStockBind', $minStock, PDO::PARAM_INT);
        }
        
        if ($minCost != 0 or is_null($minCost)) {
            $statement->bindParam(':minCostBind', $minCost, PDO::PARAM_STR);
        }
        
        if ($maxCost != 0 or is_null($maxCost)) {
            $statement->bindParam(':maxCostBind', $maxCost, PDO::PARAM_STR);
        }
    
        $statement->execute();
        
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        fatalError("PDO Exception occurred");
    }
    
    // Prepare results for Cache
    $queryData = serialize($result);
    
    // Prepare results for URL
    $results = http_build_query(array('r' => $result));
    $resultsURL = "results.php?".$results;
    
    // Close database connection
    $dbh = null;
    
    // Redirect to Results page
    header("Location: {$resultsURL}");
    exit();
?>