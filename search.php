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
            $result = $dbh->query("SELECT * FROM region");
            // Run query and check for regions
            if ($result->rowCount() > 0) {
                print "\n<select name=\"regions\">";
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
    <input type="hidden" name="searchMethod" value="wineryName">
    <br />Search by winery:
    <input type="text" name="wineryName" value="All" />
      (type All to see all regions)
    <br /><input type="submit" value="Search" />
  </form>
  <br />
  <form action="answer.php" method="POST">
    <input type="hidden" name="searchMethod" value="regionMenu">
    <br />Search by region:
    <?php buildRegionMenu($dbh); ?>
    <br /><input type="submit" value="Search" />
  </form>
</body>
</html>
<?php $dbh = null; ?>