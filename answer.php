<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

// Only search.php should access this page
if (basename($_SERVER[HTTP_REFERER]) != "search.php") {
    header("Location: search.php");
    exit;
}

// Establish database connection
require_once('db.php');
try {
    $dbh = new PDO(DB_DSN, DB_USER, DB_PW);
}
catch (PDOException $e) {
    echo '<h3>Error</h3><p>Could not connect to database</p>';
    die();
}

if (!isset(searchMethod)) {
    echo '<h3>Error</h3><p>Search method not found.</p>';
    die();
}


?>