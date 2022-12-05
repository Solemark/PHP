<?php
	$db_address = 'localhost';
	$db_user = 'webauth';
	$db_pass = 'webauth';
	$db_name = 'adoption';

	$db = new mysqli($db_address, $db_user, $db_pass, $db_name);
	if ($db->connect_error) {
		echo "Could not connect to the database.  Please try again later.";
		exit;
	}
?>