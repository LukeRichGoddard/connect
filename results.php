<?php
    require_once('common.php');
    
    if(isset($_GET["results"])) {
        // Parse URL encoding
        parse_str($_GET["results"], $results);
        
        // Output results
        echo '<pre>';
        print_r($results);
        echo '</pre>';
    } else {
        print("No results");
    }
?>