<html>
	<head>
		<title>Home - Adoption Management</title>
		<meta charset="UTF-8"/>
	</head>
	<body>
		<h1>Adoption Management Dashboard</h1>

		<?php
			session_start();
			$valid_session = require('check_session.php');
			$valid_login = require('check_login.php');
			$session_test = 0;
			if ($valid_login || $valid_session) {
				$name = $_SESSION['valid_user'];
				echo "Welcome, $name<br>";
				$session_test = 1;
			}
			else {
				if(isset($name)){
					echo "Unable to login.<br>";
				}
			}
		?>

		<?php
			$db_address = 'localhost';
			$db_user = 'webauth';
			$db_pass = 'webauth';
			$db_name = 'adoption';
			
			$db = new mysqli($db_address, $db_user, $db_pass, $db_name);
			
			if ($db->connect_error) {
				echo "Could not connect to the database";
				exit;
			}

			$query = 	"SELECT *
						FROM animal
						ORDER BY animal_type, name";
			$result = $db->query($query);
			$num_results = $result->num_rows;

			echo <<<END
				<br>
				<table border="1">
					<thead>
						<tr>
							<th>Name</th>
							<th>Animal Type</th>
							<th>Adoption fee</th>
							<th>Sex</th>
							<th>Desexed?</th>
END;
						if($session_test == 1){
							echo "<th></th>";
							echo "<th></th>";
						}
			echo <<<END
						</tr>
					</thead>
END;
			
				for ($i = 0; $i < $num_results; $i++) {
					$row = $result->fetch_assoc();
					$animalid = $row['animalid'];
					$name = $row['name'];
					$animal_type = $row['animal_type'];
					$adoption_fee = $row['adoption_fee'];
					$sex = $row['sex'];
					$desexed = $row['desexed'];	

					if($desexed == 1){
						$desexed="Yes";
					}
					else{
						$desexed="No";
					}

					echo "<tr>";
					echo "<td valign=\"top\">$name</td>";
					echo "<td valign=\"top\">$animal_type</td>";
					echo "<td valign=\"top\">$adoption_fee</td>";
					echo "<td valign=\"top\">$sex</td>";
					echo "<td valign=\"top\">$desexed</td>";
					if($session_test == 1){
						create_button_column("animalid", $animalid, "Edit", "edit.php");
						create_button_column("animalid", $animalid, "Delete", "delete.php");
					}
					echo "</tr>";
				}
				$result->free();
				$db->close();
				
				function create_button_column($hidden_name, $hidden_value, $button_text, $action_page) {
					echo "<td>";
					echo "<form action=$action_page method=\"GET\">";
					echo "<input type=\"hidden\" name=$hidden_name value=$hidden_value>";					
					echo "<button type=\"submit\">$button_text</button>";
					echo "</form>";			
					echo "</td>";
				}	
				echo "</table>";
				echo "<br>";
		?>
	</body>
	<footer style="border-top: 1px solid grey"> 
		<?php
			if($session_test == 1){
				include("footer_logout.php");
			}
			else{
				include("footer_login.php");
			}
		?>
	</footer>
</html>