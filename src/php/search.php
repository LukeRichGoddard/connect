<?php
    require_once('common.php');
    
    // Header
    include('../html/header.html');
    
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
                print "\n<option value=\"0\">All</option>";
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
  <h1>Search Winestore</h1>
  <form action="answer.php" method="GET">
    <table id="search">
      <tr>
        <td>Wine name:</td>
        <td><input type="text" name="wineName" value="All" /></td>
      </tr>
      <tr>
        <td></td>
        <td>(type All for all wines)</td>
      </tr> 
      <tr>
        <td>Winery:</td>
        <td><input type="text" name="wineryName" value="All" /></td>
      </tr>   
      <tr>
        <td>&nbsp;</td>
        <td>(type All for all wineries)</td>
      </tr> 
      <tr>
        <td>Region:</td>
        <td><?php buildRegionMenu($dbh); ?></td>
      </tr>   
      <tr>
        <td>Grape Variety:</td>
        <td><?php buildGrapeMenu($dbh); ?></td>
      </tr>   
      <tr>
        <td>Year: </td>
        <td>From <input type="text" name="minYear" size="4"/> to <input type="text" name="maxYear" size="4" /></td>
      </tr>
      <tr>
        <td>Minimum in stock: </td>
        <td><input type="text" name="minStock" value="" /></td>
      </tr>
      <tr>
        <td>Minimum ordered: </td>
        <td><input type="text" name="minOrder" value="" /></td>
      </tr>
      <tr>
        <td>Price range: </td>
        <td>From <input type="text" name="minCost" size="8"/> to <input type="text" name="maxCost" size="8" /></td>
      </tr>
      <tr>
        <td colspan="2"><input type="submit" value="Search" /></td>
      </tr>
    </table>
  </form>
<?php 
    // Close database connection
    $dbh = null;

    // Footer
    include('../html/footer.html');
?>