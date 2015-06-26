<?php
	require_once('db.php');
	if(!$dbconn = mysql_connect(DB_HOST, DB_USER, DB_PW)) {
		echo '<h3>Error</h3>Could not connect to mysql on ' . DB_HOST . '<br />';
		exit;
	}
	echo 'Connected to mysql <br />';
	if(!mysql_select_db(DB_NAME, $dbconn)) {
		echo '<h3>Error</h3>Could not use database ' . DB_NAME . '<br />';
		echo mysql_error() . '<br />';
		exit;
	}
	echo 'Connected to database ' . DB_NAME . '<br />';
?>
