<?php

ini_set('display_errors',1);
error_reporting(E_ALL);

// Only search.php should access this page
if (basename($_SERVER[HTTP_REFERRER]) != "search.php") {
    header("Location: search.php");
    exit
}

require_once('db.php');

?>