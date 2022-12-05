<html>
	<head>
		<title>Delete Animal</title>
		<meta charset="UTF-8"/>
	</head>
	<body>
		<h1>Delete Animal</h1>
		
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
					
					$query = 	"DELETE FROM animal
								WHERE animalid = ?";
							  
					$stmt = $db->prepare($query);
					$stmt->bind_param("i", $animal_id);
					$stmt->execute();				
					
					$affected_rows = $stmt->affected_rows;
					$stmt->close();
					$db->close();
					
					if ($affected_rows == 1) {
						echo "Successfully Deleted Animal<br>";
						echo "<a href=\"home.php\">Home</a>";
						echo "<br><hr>";
						exit;		
					}
					else {
						echo "Failed to Delete Animal<br>";
						echo "<a href=\"home.php\">HOME</a>";
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

					if($desexed = 1){
						$desexed = "Yes";
					}
					else{
						$desexed = "No";
					}
					
					echo <<<END
					Delete Animal with ID: <strong>$animal_id</strong><br><br>
					<form action="" method="POST">
						<table>
							<tr>
								<td>Name:</td>
								<td>$name</td>
							</tr>
							<tr>
								<td>Animal Type:</td>
								<td>$animal_type</td>
							</tr>
							<tr>
								<td>Adoption Fee:</td>
								<td>$adoption_fee</td>
							</tr>
							<tr>
								<td>Sex:</td>
								<td>$sex</td>
							</tr>
							<tr>
								<td>Desexed?:</td>
								<td>$desexed</td>
							</tr>
						</table>
						<br>
						<input type="hidden" name="animalid" value=$animal_id>
						<input type="submit" name="submit" value="Delete">
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