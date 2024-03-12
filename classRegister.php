<?php
require 'config.php';
if(!empty($_SESSION["id"])){
    header("Location: index.php");
}


if(isset($_POST["submit"])){

	
	//Obtain current year/semester
	//current sememster = latest semester from latest year from section table (The lastest year is considered the current year)
	$curYear = mysqli_query($connection, "SELECT year FROM section WHERE year = MAX(year)");
	$curSemester = mysqli_query($connection, "SELECT semester FROM section WHERE year = '$curYear' AND semester = "); //if exists?
	
	//<display options>
	
	
	//Select class (course)
	 
	//Presuming class/section is inputed (written in) manually? -> Idea: drop box?
	$SelectedClass = $_POST["selectedclass"];
	$validClass = mysqli_query($connection, "SELECT course_name FROM course WHERE selectedclass = '$SelectedClass'");
	if(!$validCalss){
        	echo "<script> alert('Error: Course not found.'); </script>";
    	}
	
	//Select section
	
	$SelectedSection = $_POST["selectedsection"];
	$validSection = mysqli_query($connection, "SELECT section_id FROM section WHERE selectedsection = '$SelectedSection'");
	if(!$validSection){
        	echo "<script> alert('Error: Section not found.'); </script>";
    	}
    	
    	//Attempt to signup- 
	
	//Check if prequiste(s) are meet
	
	$refID = mysqli_query($connection, "SELECT course_id FROM course WHERE course_name = '$SelectedClass'");
	$prereqs = mysqli_query($connection, "SELECT UNIQUE prereq_id FROM Prereq WHERE course_id = '$refID'");
	
	//Obtain userID
	$userEmail = $_SESSION["email"];
	$userID = mysqli_query($connection, "SELECT student_id FROM student WHERE email = '$userEmail'");
	
	//Return courses that user passed (grade != 'F')
	$userCoursesPassed = mysqli_query($connection, "SELECT UNIQUE course_id FROM take WHERE student_id = '$userID' AND grade != 'F'");
	
	while($pre_row = mysqli_fetch_row($prereqs) && $user_row = mysqli_fetch_row($userCoursesPassed)) {
		$valid = ($pre_row == $user_row); //Check if passed courses matches prereqs for each available row
		if (!$valid) {
			echo "<script> alert('Cannot sign up for selected course: Prerequistes not meet.'); </script>";
			break;
		}
	}
	
	//Check for schedule conflict?
	
	//Sign up
	if($valid) {
		$signup = mysqli_query($connection, "INSERT INTO take (student_id, course_id, section_id, semester, year, grade) value ($userID, $refID, $SelectedSection, $curSemester, $curYear, NULL)");
	}
	if(!$signup){
		echo "<script> alert('Error: Unable to register for course.'); </script>";
    	}
	else {
		echo "<script> alert('Class successfully registered.'); </script>";
	}

}


?>


//HTML
