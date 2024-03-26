<?php
	require 'config.php';

	if (isset($_POST["submit"]))
	{
		$course = $_POST["course"];
		$section = $_POST["section"];
		$studentName = $_POST["student"];

		$sectionNumber = str_ireplace("Section", "", $section);

		// Derive the current semester
		$currentSemesterQuery = "SELECT MAX(year) AS max_year, semester
				FROM section
				GROUP BY year
				ORDER BY year DESC, FIELD(semester, 'Spring', 'Summer', 'Fall', 'Winter')
				LIMIT 1";
		$currentResult = mysqli_query($connection, $currentSemesterQuery);
		$currentRow = $currentResult->fetch_assoc();
		$currentSemester = $currentRow["semester"];
		$currentYear = $currentRow["max_year"];

		// Check that course and section are valid
		$sectionQuery = "SELECT * FROM section
				WHERE section_id = '$section'";
		$sectionResult = mysqli_query($connection, $sectionQuery);

		$courseQuery = "SELECT * FROM section
				WHERE course_id = '$course'";
		$courseResult = mysqli_query($connection, $courseQuery);

		$courseSectionQuery = "SELECT * FROM section
				WHERE course_id = '$course'
				AND section_id = '$section'";
		$courseSectionResult = mysqli_query($connection, $courseSectionQuery);

		$currentSectionQuery = "SELECT * FROM section
				WHERE course_id = '$course'
				AND section_id = '$section'
				AND semester = '$currentSemester'
				AND year ='$currentYear'";
		$currentSectionResult = mysqli_query($connection, $currentSectionQuery);

		// Check that student is a PhD student and not a TA
		$phdResult = 0;
		$taResult = 0;
		$studentQuery = mysqli_query($connection, "SELECT * FROM student WHERE name = '$studentName'");
		if (mysqli_num_rows($studentQuery) != 0)
		{
			$studentRow = mysqli_fetch_assoc($studentQuery);
			$studentID = $studentRow["student_id"];
			$phdResult = mysqli_query($connection, "SELECT * FROM PhD WHERE student_id = '$studentID'");

			// Check that the PhD student isn't already a TA
			$taResult = mysqli_query($connection, "SELECT * FROM ta WHERE student_id = '$studentID'");
		}

		// Check that section has 10 or more students
		$classSizeQuery = "SELECT student_id
				FROM take
				WHERE course_id = '$course'
				AND section_id = '$section'
				AND semester = '$currentSemester'
				AND year ='$currentYear'";
		$classSizeResult = mysqli_query($connection, $classSizeQuery);
		
		if (mysqli_num_rows($courseResult) == 0)
		{ 
			echo '<div class="alert">' . 'Course ' . $course . ' is not recognized' . '</div>';
		}
		else if (mysqli_num_rows($sectionResult) == 0)
		{ 
			echo '<div class="alert">' . 'Section ' . $sectionNumber . ' is not recognized' . '</div>';
		}
		else if (mysqli_num_rows($courseSectionResult) == 0)
		{ 
			echo '<div class="alert">' . 'Section ' . $sectionNumber . ' not found for course ' . $course . '</div>';
		}
		else if (mysqli_num_rows($currentSectionResult) == 0)
		{ 
			echo '<div class="alert">' . 'Course ' . $course . ' Section ' . $sectionNumber . ' not on offer this semester' . '</div>';
		}
		else if ($phdResult && (mysqli_num_rows($phdResult) == 0))
		{ 
			echo '<div class="alert">' . 'Student ' . $studentName . ' is not a PhD student' . '</div>';
		}
		else if ($taResult && (mysqli_num_rows($taResult) > 0))
		{
			echo '<div class="alert">' . 'PhD Student ' . $studentName . ' is already assigned as a TA' . '</div>';
		}
		else if (mysqli_num_rows($classSizeResult) < 10)
		{
			echo '<div class="alert">' . $course . 'Section ' . $sectionNumber . ' has fewer than ten (10) students' . '</div>';
		}
		else
		{
			$insertTAQuery = "INSERT INTO ta
					VALUES ('$studentID', '$course', '$section', '$currentSemester', '$currentYear')";
			mysqli_query($connection, $insertTAQuery);
			echo '<div class="msg">' . 'PhD Student ' . $studentName . ' successfully assigned as a TA to ' . $course . ' Section ' . $sectionNumber . '</div>';
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
		<form class="" action="" method = "post" autocomplete="on">
			<label for="course"> Course: </label>
			<input type="text" name="course" id="course" required>

			<label for="section"> Section: </label>
			<input type="text" name="section" id="section" required> <br>

			<label for="student"> PhD Student: </label>
			<input type="text" name="student" id="student" required> <br>



			<button type="submit" name="submit">Submit </button>
		</form>
		<br>

		
		<a href="adminIndex.php"> Go Back </a> <br>
		<a href="logout.php"> Logout </a> <br>
	</body>
	
	<style>
    	.alert {border:1px solid #bbb; padding:5px; margin:10px 0px; background:#ec7063;}
		.msg {border:1px solid #bbb; padding:5px; margin:10px 0px; background:#58d68d;}
	</style>
</html>