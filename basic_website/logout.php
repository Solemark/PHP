<?php
	session_start();
	$valid_session = require('check_session.php');
	
	if ($valid_session) {
		$old_user = $_SESSION['valid_user'];
		unset($_SESSION['valid_user']);
		session_destroy();		
	}
	
	if (!empty($old_user)) {
		echo "Logged Out.<br><br>";

	}
	else {
		echo "Unable to logout.<br><br>";
	}
?>
<footer style="border-top: 1px solid grey"> 
	<?php
		echo "<a href=\"home.php\" >Home</a> | ";
		echo "<a href=\"login.php\">Login</a>";
	?>
</footer>
