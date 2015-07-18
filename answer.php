<?php
    // Only search.php should access this page
    if (basename($_SERVER["HTTP_REFERER"]) != "search.php") {
        header("Location: search.php");
        exit;
    }
    
    require_once('common.php');
    
    //
    // FUNCTIONS
    //
    
    // wineNameSearch
    function wineNameSearch($dbh) {
        // Validate input
        $wineName = sanitise($_GET["wineName"]);
        
        // Prepare query
        $search = "SELECT * 
                  FROM   wine
                  WHERE  wine.wine_name
                  LIKE   '%:wineName%'";
        
        // Execute query
        try {
            $statement = $dbh->prepare($search);
            $statement->execute(array(':wineName' => $wineName));
            $result = $statement->fetchAll();
            
        } catch (PDOException $e) {
            fatalError("PDO Exception occurred");
        }
        
        // Prepare results for URL
        print_r($result);
        
    }
    
    // wineryNameSearch
    function wineryNameSearch($dbh) {
        
    }
    
    // regionMenuSearch
    function regionMenuSearch($dbh) {
        
    }
    
    // grapeMenuSearch
    function grapeMenuSearch($dbh) {
        
    }
    
    // yearsSearch
    function yearsSearch($dbh) {
        
    }
    
    // wineStockSearch
    function wineStockSearch($dbh) {
        
    }
    
    // wineOrderSearch
    function wineOrderSearch($dbh) {
        
    }
    
    // costSearch
    function costSearch($dbh) {
        
    }

    //
    // MAIN
    //
    
    // Establish database connection
    require_once('db.php');
    try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PW);
    }
    catch (PDOException $e) {
        fatalError("Could not connect to database");
    }

    // Ensure searchMethod exists
    if (!isset($_GET["searchMethod"])) {
        fatalError("Search method not found");
    }
    
    // Sanitise searchMethod
    $searchMethod = sanitise($_GET["searchMethod"]);

    // Ensure searchMethod is not null
    if (is_null($searchMethod)) {
        fatalError("Search method not found");
    }
    
    // Select method for search
    switch ($searchMethod) {
        
        case "wineName":
            wineNameSearch($dbh);
            break;
            
        case "wineryName":
            wineryNameSearch($dbh);
            break;
        
        case "regionMenu":
            regionMenuSearch($dbh);
            break;
            
        case "grapeMenu":
            grapeMenuSearch($dbh);
            break;
        
        case "years":
            yearsSearch($dbh);
            break;
        
        case "wineStock":
            wineStockSearch($dbh);
            break;
        
        case "wineOrder":
            wineOrderSearch($dbh);
            break;
        
        case "cost":
            costSearch($dbh);
            break;
        
        default:
            fatalError("Search method not recognised");
    }
    
    // Close database connection
    $dbh = null;
?>