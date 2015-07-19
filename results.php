<?php
    require_once('common.php');
    
    // Recreate array from cache
    // Based on code found at http://stackoverflow.com/questions/15746138/
    if(isset($_GET["query"]) {
        $queryChecksum = sanitise($_GET["query"]);
        $cacheFile = "~/cache/{$queryChecksum}.data";
        
        // Load data from cache
        if(file_exists($cacheFile)) {
            $results = unserialize($cacheFile); 
        } else {
            fatalError("Query cache not found");
        }
    }
    
    if(isset($_GET["r"])) {
        
        // Output results
        echo "<table>";
        echo "<tr>";
        echo "<td>ID</td>";
        echo "<td>Wine</td>";
        echo "<td>Grape Variety</td>";
        echo "<td>Year</td>";
        echo "<td>Winery</td>";
        echo "<td>Region</td>";
        echo "<td>Cost</td>";
        echo "<td>On Hand</td>";
        echo "<td>Total Sold</td>";
        echo "<td>Total Revenue</td>";
        echo "</tr>";
        
        // loop over results
        foreach ($_GET["r"] as $result) {
            echo "<tr>";
            echo "<td>{$result["wine_id"]}</td>";
            echo "<td>{$result["wine_name"]}</td>";
            echo "<td>{$result["wine_variety"]}</td>";
            echo "<td>{$result["year"]}</td>";
            echo "<td>{$result["winery_name"]}</td>";
            echo "<td>{$result["region_name"]}</td>";
            echo "<td>{$result["cost"]}</td>";
            echo "<td>{$result["on_hand"]}</td>";
            echo "<td>{$result["total_sold"]}</td>";
            echo "<td>{$result["total_revenue"]}</td>";
            echo "</tr>";
        }
        
        echo '</table>';
        
    } else {
        print("No results");
    }
?>