<?php
    require_once('common.php');
    
    if(isset($_GET["results"])) {
        // Parse URL encoding
        parse_str($_GET["results"], $results);
        
        // Output results
        // echo '<pre>';
        // print_r($_GET["results"]);
        // echo '</pre>';
        echo "<table>";
        echo "<tr>";
        echo "<td>wine_id</td>";
        echo "<td>wine_name</td>";
        echo "<td>wine_type</td>";
        echo "<td>year</td>";
        echo "<td>winery_id</td>";
        echo "</tr>";
        
        // loop over results
        foreach ($results as $result) {
            echo "<tr>";
            echo "<td>{$result["wine_id"]}</td>";
            echo "<td>wine_name</td>";
            echo "<td>wine_type</td>";
            echo "<td>year</td>";
            echo "<td>winery_id</td>";
            echo "</tr>";
        }
        
        echo '</table>';
        
    } else {
        print("No results");
    }
?>