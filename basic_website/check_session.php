<?php
	if (isset($_SESSION['valid_user'])) {
		return true;
	}
	else {
		return false;
	}	
?>
