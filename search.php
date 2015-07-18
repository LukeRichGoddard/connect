<?php
    require_once('common.php');
    
    // Establish database connection
    require_once('db.php');
    try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PW);
    }
    catch (PDOException $e) {
        fatalError("Could not connect to database");
    }
    echo '<!-- Connected to mysql database ' . DB_NAME . ' -->';
	
	// Build drop down menu for selecting regions
	function buildRegionMenu($dbh) {
        try {
            // Prepare query
            $result = $dbh->query("SELECT region.region_id,
                                          region.region_name
                                   FROM   region");
            // Run query and check for regions
            if ($result->rowCount() > 0) {
                print "\n<select name=\"region\">";
                // Add regions to drop down menu
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "\n<option value=\"".$row['region_id']."\">".$row['region_name']."</option>";
                }
                print "\n</select>";
            } else {
                // Error: No regions found
                print "<p>Error: no regions found in " . DB_NAME . "</p>";
            }
        } catch (PDOException $e) {
            fatalError("PDO Exception occurred");
        }
	}
    
    // Build drop down menu for selecting grape varieties
	function buildGrapeMenu($dbh) {
        try {
            // Prepare query
            $result = $dbh->query("SELECT grape_variety.variety_id,
                                          grape_variety.variety
                                   FROM   grape_variety");
            // Run query and check for grape varieties
            if ($result->rowCount() > 0) {
                print "\n<select name=\"grape_variety\">";
                print "\n<option value=\"-1\">All</option>";
                // Add regions to drop down menu
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    print "\n<option value=\"{$row['variety_id']}\">{$row['variety']}</option>";
                }
                print "\n</select>";
            } else {
                // Error: No grape varieties found
                print "<p>Error: no grape varieties found in " . DB_NAME . "</p>";
            }
        } catch (PDOException $e) {
            fatalError("PDO Exception occurred");
        }
	}
    
?>
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>Search Winestore</title>
</head>
<body>
  <h1>Search Winestore</h1>
  <form action="answer.php" method="GET">
    <br />Wine name: <input type="text" name="wineName" value="All" />
    <br />(type All for all wines)
    
    <br />Winery: <input type="text" name="wineryName" value="All" />
    <br />(type All for all wineries)
    
    <br />Region:
    <?php buildRegionMenu($dbh); ?>
    
    <br />Grape Variety:
    <?php buildGrapeMenu($dbh); ?>
    
    <br />Year:
    From <input type="text" name="minYear" value="" />
    to <input type="text" name="maxYear" value="" />
    <br />
    
    <br />Minimum in stock: <input type="text" name="minStock" value="" />
    <br />
    
    <br />Minimum ordered: <input type="text" name="minOrder" value="" />
    <br />
    
    <br />Price range:
    From $<input type="text" name="minCost" value="" />
    to $<input type="text" name="maxCost" value="" />
    
    <br /><input type="submit" value="Search" />
  </form>
  <br />
</body>
</html>
<?php $dbh = null; ?>