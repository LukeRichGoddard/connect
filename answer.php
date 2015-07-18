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
    function wineNameSearch() {
        // Validate input
        $wineName = sanitise($_GET["wineName"])
        
        // Prepare query
        $query = 'SELECT * 
                  FROM   wine
                  WHERE  wine.wine_name
                  LIKE   "%:wineName%"';
        
        // Execute query
        try {
            $statement = $dbh->prepare($query);
            $statement->execute(array(':wineName' => $wineName));
            $result = $statement->fetchAll();
            
        } catch (PDOException $e) {
            fatalError("PDO Exception occurred");
        }
        
        // Prepare results for URL
        print_r($result);
        
    }
    
    // wineryNameSearch
    function wineryNameSearch() {
        
    }
    
    // regionMenuSearch
    function regionMenuSearch() {
        
    }
    
    // grapeMenuSearch
    function grapeMenuSearch() {
        
    }
    
    // yearsSearch
    function yearsSearch() {
        
    }
    
    // wineStockSearch
    function wineStockSearch() {
        
    }
    
    // wineOrderSearch
    function wineOrderSearch() {
        
    }
    
    // costSearch
    function costSearch() {
        
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
            wineNameSearch();
            break;
            
        case "wineryName":
            wineryNameSearch();
            break;
        
        case "regionMenu":
            regionMenuSearch();
            break;
            
        case "grapeMenu":
            grapeMenuSearch();
            break;
        
        case "years":
            yearsSearch();
            break;
        
        case "wineStock":
            wineStockSearch();
            break;
        
        case "wineOrder":
            wineOrderSearch();
            break;
        
        case "cost":
            costSearch();
            break;
        
        default:
            fatalError("Search method not recognised");
    }
    
    // Close database connection
    $dbh = null;
?>