<?php
	$action_page="home.php";
	echo <<< END
	<form method="POST" action="$action_page">
	<p>Username: <input type="text" name="name"></p>
	<p>Password: <input type="password" name="password"></p>
	<p><input type="submit" name="submit" value="Log In"></p>
	</form>	
END;
?>
<footer style="border-top: 1px solid grey"> 
	<?php
		include("footer_login.php");
	?>
</footer>