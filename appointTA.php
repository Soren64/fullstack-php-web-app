<?php
	require 'config.php';

	if(isset($_POST["submit"])){
		$section = $_POST["section"];
		$studentName = $_POST["student"];

		//check that student is a PhD student
		$sQuery = mysqli_query($connection, "SELECT * FROM student WHERE name = '$studentName'");
		$sRow = mysqli_fetch_assoc($sQuery);
		$sId = $sRow["student_id"];
		$phdQuery = mysqli_query($connection, "SELECT * FROM PhD WHERE student_id = '$sId'");

		// check that PhD student isn't already a TA
		$taQuery = mysqli_query($connection, "SELECT * FROM ta WHERE student_id = '$sId'");

		if(mysqli_num_rows($phdQuery) == 0){ 
			echo "<script> alert('Student is not a PhD student'); </script>";
			header("Location: appointTA.php");

		}
		else if (mysqli_num_rows($taQuery) > 0){
			echo "<script> alert('PhD Student is already assigned as a TA')";
			header("Location: appointAdvisor.php");

		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Index</title>
	</head>
	
	<body>
		<h1> Appoint Teaching Assistants: </h1>
		<form class="" action="" method = "post" autocomplete="off">
			<label for="section"> Section: </label>
			<input type="text" name="section" id="section" required> <br>

			<label for="student"> PhD Student: </label>
			<input type="text" name="student" id="student" required> <br>



			<button type="submit" name="submit">Submit </button>
		</form>
		<br>

	</body>
</html>