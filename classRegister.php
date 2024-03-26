<?php
require 'config.php';

//Process form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
          if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $courseId = $_POST['course'];
            $sectionId = $_POST['section'];

            //Check if user meets prereqs
	    function checkPrerequisites($conn, $userId, $courseId)
	    {
            	//Fetch all prerequisite IDs for the given course
            	$sql = "SELECT prereq_id FROM prereq WHERE course_id = $courseId";
            	$result = $conn->query($sql);
            	$prereqIds = [];
            	while ($row = $result->fetch_assoc()) {
            		$prereqIds[] = $row['prereq_id'];
    	    	}


    	    	//Check if user has passed all prerequisites
    	    	foreach ($prereqIds as $prereqId) {
            		$sql = "SELECT * FROM take WHERE user_id = $userId AND course_id = $prereqId AND grade NOT IN ('F', NULL)";
            		$result = $conn->query($sql);
            		if ($result->num_rows == 0) {
            			return false; //User has not passed a prerequisite
            		}
    	    	}
    		return true; //User has passed all prerequisites
	    }                   

	    if (checkPrerequisites($connection, $userId, $courseId)) {

        	    //User meets prerequisites, proceed with enrollment


        	    //Obtain userID
	            $userEmail = $_SESSION["email"];
	            $userID = mysqli_query($connection, "SELECT student_id FROM student WHERE email = '$userEmail'");

        	    $grade = null;
        	    $signup = mysqli_query($connection, "INSERT INTO take (student_id, course_id, section_id, semester, year, grade) VALUES ($userId, $courseId, $sectionId, " . $row["max_year"] . ", '" . $row["semester"] . "', $grade)");

        	    if ($signup === TRUE) {
            		    echo "<p>Enrolled successfully.</p>";
        	    } else {
            	    	    echo "<p>Error enrolling.</p>";
        	    }
    	    } else {
        	    echo "<p>Error: User does not meet prerequisites.</p>";
    	    }
        }
}
//Derive the current semester
$sql = "SELECT MAX(year) AS max_year, semester
        FROM section
        GROUP BY year
        ORDER BY year DESC, FIELD(semester, 'Spring', 'Summer', 'Fall', 'Winter')
        LIMIT 1";
$result = mysqli_query($connection, $sql);
$row = $result->fetch_assoc();
$currentSemester = $row["semester"] . " " . $row["max_year"];

//Fetch courses from the database
$sql = "SELECT course_id, course_name FROM course";
$result = mysqli_query($connection, $sql);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Course Enrollment</title>
</head>
<body>
    <h1>Course Enrollment</h1>
    <p>Current Semester: <?php echo $currentSemester; ?></p>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="course">Select a Course:</label>
        <select id="course" name="course">
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["course_id"] . "'>" . $row["course_name"] . "</option>";
                }
            } else {
                echo "<option disabled selected>No courses found for this semester.</option>";
            }
            ?>
        </select>
        <br>
 	<label for="semester">Section:</label>
        <input type="text" name="section" id="section"><br>
        </select>
	<br>
        <input type="submit" value="Enroll"> <br>
    </form>
    <a href="index.php">Return</a> <br>
</body>
</html>
