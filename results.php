<?php
    require_once('common.php');
    
    // Parse URL encoding
    parse_str($GET["results"], $results);
    
    if(!is_null($results)) {
        // Output results
        echo '<pre>';
        print_r($results);
        echo '</pre>';
    } else {
        print("No results");
    }
?>