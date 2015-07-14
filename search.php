<?php
	error_reporting(E_ALL);
    require_once('db.php');
	if(!$dbconn = mysql_connect(DB_HOST, DB_USER, DB_PW)) {
		echo '<h3>Error</h3><p>Could not connect to mysql on ' . DB_HOST . '</p><br />';
		exit;
	}
	echo '<!-- Connected to mysql -->';
	if(!mysql_select_db(DB_NAME, $dbconn)) {
		echo '<h3>Error</h3>Could not use database ' . DB_NAME . '<br />';
		echo mysql_error() . '<br />';
		exit;
	}
	echo '<!-- Connected to database ' . DB_NAME . ' -->';
	
  function showerror() {
     die("Error " . mysql_errno() . " : " . mysql_error());
  }
	// Build drop down menu for selecting regions
	function buildRegionMenu() {
        // Query
		$query = "SELECT region.region_id,
                         region.region_name 
                  FROM   region";
        // Run query
        $result = @ mysql_query($query, $dbconn);
		
        // Check for regions
        $rowsFound = @ mysql_num_rows($result);
        if ($rowsFound > 0) {
            print "\n<select name=\"regions\">";
            // Add regions to drop down menu
            while ($row = @ mysql_fetch_array($result)) {
                print "\n<option value=\"{$row["region_id"]}\">{$row["region_name"]}</option>";
            }
            print "\n</select>";
        } else {
            // Error: No regions found
            print "<p>Error: no regions found in " . DB_NAME . "</p>";
        }
	}
?>
<?xml version="1.0" encoding="utf-8"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <title>Search Winestore</title>
</head>
<body>
  <form action="action.php" method="POST">
    <br />Search by winery:
    <input type="text" name="wineryName" value="All" />
      (type All to see all regions)
    <br /><input type="submit" value="Search" />
  </form>
  <br />
  <form action="action.php" method="POST">
    <br />Search by region:
    <?php buildRegionMenu() ?>
    <br /><input type="submit" value="Search" />
  </form>
</body>
</html>