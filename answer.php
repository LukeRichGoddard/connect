<?php
    // Enable errors
    ini_set('display_errors',1);
    error_reporting(E_ALL);

    // Only search.php should access this page
    if (basename($_SERVER[HTTP_REFERER]) != "search.php") {
        header("Location: search.php");
        exit;
    }

    // Establish database connection
    require_once('db.php');
    try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PW);
    }
    catch (PDOException $e) {
        echo '<h3>Error</h3><p>Could not connect to database</p>';
        die();
    }

    // Sanitise function
    // Based on code from http://www.w3schools.com/php/php_form_validation.asp
    function sanitise($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Sanitise searchMethod
    $searchMethod = sanitise($_GET["searchMethod"]);

    // Ensure searchMethod exists
    //if (is_null($searchMethod)) {
    //    echo '<h3>Error</h3><p>Search method not found.</p>';
    //    die();
    //}

    // TEMP: just checking
    echo 'Search Method ' . $searchMethod . ' selected.';

    // Select method for search
    switch ($searchMethod) {
        
        case "wineName":
            break;
            
        case "wineryName":
            break;
        
        case "regionMenu":
            break;
            
        case "grapeMenu":
            break;
        
        case "years":
            break;
        
        case "wineStock":
            break;
        
        case "wineOrder":
            break;
        
        case "cost":
            break;
        
        default:
            fatalError("Search method not recognised");
    }
    
    // Close database connection
    $dbh = null;
?>