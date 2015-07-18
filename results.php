<?php
    require_once('common.php');
    
    if(isset($GET["results"])) {
    // Output results
        echo '<pre>';
        print_r($GET["results"]);
        echo '</pre>';
    } else {
        print("No results");
    }
?>