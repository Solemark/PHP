<html>
	<head>
		<title>Add Animal</title>
		<meta charset="UTF-8"/>
	</head>
	<body>
		<h1>Add Animal</h1>
		
		<?php
			session_start();
			$valid_session = require('check_session.php');

			$db_address = 'localhost';
			$db_user = 'webauth';
			$db_pass = 'webauth';
			$db_name = 'adoption';
			$session_test = 0;

			$db = new mysqli($db_address, $db_user, $db_pass, $db_name);
			if ($db->connect_error) {
				echo "Could not connect to the database";
				exit;
			}	
			if($valid_session){
				$session_test = 1;
				if (isset($_POST['submit'])) {
					$submit = $_POST['submit'];
					if ($submit == "Cancel") {
						$db->close();
						header('location: home.php');
						exit;
					}	
				
					if (!isset($_POST['name']) || empty($_POST['name'])) {
						echo "Error: Name not supplied.";
						$db->close();
						exit;
					}
					if (!isset($_POST['animal_type'])) {
						echo "Error: Animal Type not supplied.";
						$db->close();
						exit;
					}
					if (!isset($_POST['adoption_fee']) || empty($_POST['adoption_fee'])) {
						echo "Error: Adoption Fee not supplied.";
						$db->close();
						exit;
					}
					if (!isset($_POST['sex'])) {
						echo "Error: Sex not supplied.";
						$db->close();
						exit;
					}
					if (!isset($_POST['desexed'])) {
						echo "Error: Desexed status not supplied.";
						$db->close();
						exit;
					}
				
					$name = $_POST['name'];
					$animal_type = $_POST['animal_type'];
					$adoption_fee = $_POST['adoption_fee'];
					$sex = $_POST['sex'];
					$desexed = $_POST['desexed'];

					$query = 	"INSERT INTO animal (name
													,animal_type
													,adoption_fee
													,sex
													,desexed)
								VALUES (?, ?, ?, ?, ?)";
					
					$stmt = $db->prepare($query);
					$stmt->bind_param("ssisi", $name, $animal_type, $adoption_fee, $sex, $desexed);
					$stmt->execute();				
					
					$affected_rows = $stmt->affected_rows;
					$stmt->close();
					$db->close();
					
					if ($affected_rows == 1) {
						echo "Successfully Added Animal<br>";
						echo "<a href=\"home.php\">Home</a>";
						echo "<br><hr>";
						exit;		
					}
					else {
						echo "Failed to Add Home<br>";
						echo "<a href=\"home.php\">HOME</a>";
						echo "<br><hr>";
						exit;				
					}
				}
				else {
					echo <<<END
					<form action="" method="POST">
						<table>
							<tr>
								<td>Name:</td>
								<td><input type="text" name="name"</td>
							</tr>
							<tr>
								<td>Animal Type:</td>
								<td>
								<select name="animal_type">
									<option value=Dog>Dog</option>
									<option value=Cat>Cat</option>
									<option value=Bird>Bird</option>
								</select>
								</td>

							</tr>
							<tr>
								<td>Adoption Fee ($):</td>
								<td><input type="text" name="adoption_fee"></td>
							</tr>
							<tr>
								<td>Sex:</td>
								<td>
								<select name="sex">
									<option value=Male>Male</option>
									<option value=Female>Female</option>
								</select>
								</td>
							</tr>
							<tr>
								<td>Desexed?:</td>
								<td>
								<select name="desexed">
									<option value="1">Yes</option>
									<option value="0">No</option>
								</select>
								</td>
							</tr>
						</table>
						<br>
						<input type="submit" name="submit" value="Submit">
						<input type="submit" name="submit" value="Cancel">
					</form>
END;
				}
			}
			else{
				echo "Error, not logged in.";
				include("login.php");
			}
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