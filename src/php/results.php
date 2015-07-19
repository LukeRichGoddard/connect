<?php
    require_once('common.php');
    
    // Header
    include('../html/header.html');
    
    // Recreate array from cache
    // Based on code found at http://stackoverflow.com/questions/15746138/
    if(isset($_GET["query"])) {
        $queryChecksum = sanitise($_GET["query"]);
        $cacheFile = "/home/ubuntu/cache/{$queryChecksum}.data";
        
        // Load data from cache
        if(file_exists($cacheFile)) {
            $results = unserialize(file_get_contents($cacheFile)); 
        } else {
            fatalError("Query cache not found");
        }
    }
    
    echo "<h1>Winestore Search Results</h1>";
    if(isset($results)) {
        
        // Output results
        echo "<table id=\"results\">";
        echo "<tr>";
        echo "<th>Wine</th>";
        echo "<th>Grape Variety</th>";
        echo "<th>Year</th>";
        echo "<th>Winery</th>";
        echo "<th>Region</th>";
        echo "<th>Cost</th>";
        echo "<th>On Hand</th>";
        echo "<th>Total Sold</th>";
        echo "<th>Total Revenue</th>";
        echo "</tr>\n";
        
        // loop over results
        foreach ($results as $result) {
            echo "<tr>";
            echo "<td>{$result["wine_name"]}</td>";
            echo "<td>{$result["wine_variety"]}</td>";
            echo "<td>{$result["year"]}</td>";
            echo "<td>{$result["winery_name"]}</td>";
            echo "<td>{$result["region_name"]}</td>";
            echo "<td class=\"currency\">\${$result["cost"]}</td>";
            echo "<td>{$result["on_hand"]}</td>";
            echo "<td>{$result["total_sold"]}</td>";
            echo "<td class=\"currency\">\${$result["total_revenue"]}</td>";
            echo "</tr>\n";
        }
        
        echo '</table>';
        
    } else {
        print("No results");
    }
    
    // Footer
    include('../html/footer.html');
?>