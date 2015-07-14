<?php
	function init() {
        ini_set('display_errors',1);
		error_reporting(E_ALL);
		require_once('db.php');
		try {
			$dbh = new PDO('mysql:host=localhost;dbname=winestore;charset=utf8', DB_USER, DB_PW);
		}
		catch (PDOException $e) {
			echo '<h3>Error</h3><p>Could not connect to mysql on ' . DB_HOST . '</p><br />';
			die();
		}
		echo '<!-- Connected to mysql database ' . DB_NAME . ' -->';
	}
	
	function showtables() {
		$stmt = $dbh->query("SHOW tables");
        $count = $stmt->rowCount();
        print("$count tables found. \n");
	}
	
	// Build drop down menu for selecting regions
	function buildRegionMenu() {
        try {
        }
        catch (PDOException $e) {
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
  <?php init(); showtables(); ?>
  <form action="action.php" method="POST">
    <br />Search by winery:
    <input type="text" name="wineryName" value="All" />
      (type All to see all regions)
    <br /><input type="submit" value="Search" />
  </form>
  <br />
  <form action="action.php" method="POST">
    <br />Search by region:
    <?php buildRegionMenu(); ?>
    <br /><input type="submit" value="Search" />
  </form>
</body>
</html>
<?php $dbh = null; ?>