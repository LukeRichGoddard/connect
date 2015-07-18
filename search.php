<?php
    ini_set('display_errors',1);
    error_reporting(E_ALL);
    require_once('db.php');
    try {
        $dbh = new PDO(DB_DSN, DB_USER, DB_PW);
    }
    catch (PDOException $e) {
        echo '<h3>Error</h3><p>Could not connect to database</p>';
        die();
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
            echo '<h3>Error</h3><p>PDO Exception occurred</p><br />';
			die();
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
                // Add regions to drop down menu
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo "\n<option value=\"".$row['variety_id']."\">".$row['variety']."</option>";
                }
                print "\n</select>";
            } else {
                // Error: No grape varieties found
                print "<p>Error: no grape varieties found in " . DB_NAME . "</p>";
            }
        } catch (PDOException $e) {
            echo '<h3>Error</h3><p>PDO Exception occurred</p><br />';
			die();
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
  
  <form action="answer.php" method="POST">
    <input type="hidden" name="searchMethod" value="wineName">
    <br />Search by wine name:
    <input type="text" name="wineName" value="All" /> (type All to see all wines)
    <br /><input type="submit" value="Search" />
  </form>
  <br />

  <form action="answer.php" method="POST">
    <input type="hidden" name="searchMethod" value="wineryName">
    <br />Search by winery:
    <input type="text" name="wineryName" value="All" /> (type All to see all wineries)
    <br /><input type="submit" value="Search" />
  </form>
  <br />
  
  <form action="answer.php" method="POST">
    <input type="hidden" name="searchMethod" value="regionMenu">
    <br />Search by region:
    <?php buildRegionMenu($dbh); ?>
    <br /><input type="submit" value="Search" />
  </form>
  <br />
  
  <form action="answer.php" method="POST">
    <input type="hidden" name="searchMethod" value="grapeMenu">
    <br />Search by grape variety:
    <?php buildGrapeMenu($dbh); ?>
    <br /><input type="submit" value="Search" />
  </form>
  <br />
  
  <form action="answer.php" method="POST">
    <input type="hidden" name="searchMethod" value="years">
    <br />Search by years:
    From <input type="text" name="minYear" value="" />
    to <input type="text" name="maxYear" value="" />
    <br /><input type="submit" value="Search" />
  </form>
  <br />
  
  <form action="answer.php" method="POST">
    <input type="hidden" name="searchMethod" value="wineStock">
    <br />Search by minimum number of wine(s) in stock, per wine:
    <br /><input type="submit" value="Search" />
  </form>
  <br />
  
  <form action="answer.php" method="POST">
    <input type="hidden" name="searchMethod" value="wineOrder">
    <br />Search by minimum number of wines ordered, per wine:
    <br /><input type="submit" value="Search" />
  </form>
  <br />
  
  <form action="answer.php" method="POST">
    <input type="hidden" name="searchMethod" value="cost">
    <br />Search by dollar cost range:
    From <input type="text" name="minCost" value="0" />
    to <input type="text" name="maxCost" value="0" />
    <br /><input type="submit" value="Search" />
  </form>
  <br />
  
</body>
</html>
<?php $dbh = null; ?>