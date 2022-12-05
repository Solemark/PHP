<html>
	<head>
		<title>Edit Animal</title>
		<meta charset="UTF-8"/>
	</head>
	<body>
		<h1>Edit animal</h1>
		
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
				if (!isset($_GET['animalid']) || empty($_GET['animalid'])) {
					echo "Error: Animal ID not supplied.";
					$db->close();
					exit;
				}
				$animal_id = $_GET['animalid'];		
				
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
						echo "Error: Animal type not supplied.";
						$db->close();
						exit;
					}
					if (!isset($_POST['adoption_fee']) || empty($_POST['adoption_fee'])) {
						echo "Error: Adoption fee not supplied.";
						$db->close();
						exit;
					}
					if (!isset($_POST['sex'])) {
						echo "Error: sex not supplied.";
						$db->close();
						exit;
					}
					if (!isset($_POST['desexed'])) {
						echo "Error: desexed status not supplied.";
						$db->close();
						exit;
					}
				
					$name = $_POST['name'];
					$animal_type = $_POST['animal_type'];
					$adoption_fee = $_POST['adoption_fee'];
					$sex = $_POST['sex'];
					$desexed = $_POST['desexed'];
					
					$query = "	UPDATE animal
								SET name = ?,
								    animal_type = ?,
								    adoption_fee = ?,
								    sex = ?,
								    desexed = ?
							 	WHERE animalid = ?";
							  
					$stmt = $db->prepare($query);
					$stmt->bind_param("ssisii", $name, $animal_type, $adoption_fee, $sex, $desexed, $animal_id);
					$stmt->execute();				
					
					$affected_rows = $stmt->affected_rows;
					$stmt->close();
					$db->close();
					
					if ($affected_rows == 1) {
						echo "Successfully Updated Animal<br>";
						echo "<a href=\"home.php\">Back to Home</a>";
						echo "<br><hr>";
						exit;		
					}
					else {
						echo "Failed to Update Animal<br>";
						echo "<a href=\"home.php\">Back to home</a>";
						echo "<br><hr>";
						exit;				
					}
				}
				else {
					$query_animal_details = 	"SELECT *
												FROM animal
												WHERE animalid = ?";
					$stmt_animal_details = $db->prepare($query_animal_details);
					$stmt_animal_details->bind_param("i", $animal_id);
					$stmt_animal_details->execute();
					
					$result = $stmt_animal_details->get_result();
					$stmt_animal_details->close();
					
					$row = $result->fetch_assoc();
					
					$name = $row['name'];
					$animal_type = $row['animal_type'];
					$adoption_fee = $row['adoption_fee'];
					$sex = $row['sex'];
					$desexed = $row['desexed'];
					
					echo <<<END
					Editing animal with ID: <strong>$animal_id</strong><br><br>
					<form action="" method="POST">
						<table>
							<tr>
								<td>Name:</td>
								<td><input type="text" name="name" value="$name"></td>
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
								<td><input type="text" name="adoption_fee" value="$adoption_fee"></td>
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
									<option value=1>Yes</option>
									<option value=0>No</option>
								</select>
								</td>
							</tr>
						</table>
						<br>
						<input type="hidden" name="animalid" value=$animal_id>
						<input type="submit" name="submit" value="Submit Changes">
						<input type="submit" name="submit" value="Cancel">
					</form>
END;
					$result->free();
				}
				$db->close();
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