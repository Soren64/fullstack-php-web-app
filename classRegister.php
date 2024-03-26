<?php
require 'config.php';

//Derive the current semester (for visual)
$sql = "SELECT MAX(year) AS max_year, semester
        FROM section
        GROUP BY year
        ORDER BY year DESC, FIELD(semester, 'Spring', 'Summer', 'Fall', 'Winter')
        LIMIT 1";
$result = mysqli_query($connection, $sql);
$row = $result->fetch_assoc();
$currentSemester = $row["semester"] . " " . $row["max_year"];

//Process form submissions
if (isset($_POST["submit"])) {
	
  	//Derive the current semester
    $sql = "SELECT MAX(year) AS max_year, semester
            FROM section
            GROUP BY year
            ORDER BY year DESC, FIELD(semester, 'Spring', 'Summer', 'Fall', 'Winter')
            LIMIT 1";
    $result = mysqli_query($connection, $sql);
    $row = $result->fetch_assoc();
    
        $currentSemester = $row["semester"];
        
        // Obtain user ID from the email
        $userEmail = $_SESSION["email"];
        $userIDQuery = mysqli_query($connection, "SELECT student_id FROM student WHERE email = '$userEmail'");
        $userIDResult = mysqli_fetch_assoc($userIDQuery);
        $userId = $userIDResult['student_id'];

        $courseId = $_POST['course'];
        $sectionId = $_POST['section'];
        $grade = NULL;

        //Check if user meets prereqs
	    function checkPrerequisites($conn, $uId, $cId)
	    {
            	//Fetch all prerequisite IDs for the given course
            	$sql = "SELECT prereq_id FROM prereq WHERE course_id = '$cId'";
            	$result = $conn->query($sql);
            	$prereqIds = [];
            	while ($row = $result->fetch_assoc()) {
            		$prereqIds[] = $row['prereq_id'];
    	    	}


    	    	//Check if user has passed all prerequisites
    	    	foreach ($prereqIds as $prereqId) {
            		$sql = "SELECT * FROM take WHERE student_id = '$uId' AND course_id = '$prereqId' AND (grade <> 'F' OR grade IS NULL)";
            		$result = $conn->query($sql);
            		if ($result->num_rows == 0) {
            			return false; //User has not passed a prerequisite
            		}
    	    	}
    		return true; //User has passed all prerequisites
	    }   


	//Check if course ID is valid
	$sql = "SELECT * FROM course WHERE course_id = '$courseId'";
	$result = $connection->query($sql);

	if ($result->num_rows > 0) {
    	//Course ID is valid, now check if section ID is valid for the current semester
    	$sql = "SELECT * FROM section WHERE section_id = '$sectionId' AND course_id = '$courseId' AND year = " . $row["max_year"] . " AND semester = '" . $row["semester"] . "'";
    	$result = $connection->query($sql);

    		if ($result->num_rows > 0) {
        		//Section ID is valid for the current semester, check prerequisites
        		if (checkPrerequisites($connection, $userId, $courseId)) {
            		//User meets prerequisites, proceed with enrollment
  	    		$grade = NULL;
            		//Enroll the student in the course section
            		$signup = mysqli_query($connection, "INSERT INTO take (student_id, course_id, section_id, semester, year, grade) VALUES ('$userId', '$courseId', '$sectionId', '$currentSemester', " . $row["max_year"] . ", '$grade')");
        			if ($signup === TRUE) {
					//echo "Enrollment successful.";
					echo '<div class="msg">' . 'Enrollment successful.' . '</div>';

				} else {
					//echo "Error: Unable to enroll.";
					echo '<div class="alert">' . 'Unable to enroll.' . '</div>';
				}
			} else {
            			//echo "Error: User does not meet prerequisites.";
						echo '<div class="alert">' . 'User does not meet prerequisites.' . '</div>';
        		}
    		} else {
        		//echo "Error: Invalid section ID for the current semester.";
				echo '<div class="alert">' . 'Invalid section ID for the current semester.' . '</div>';

    		}
	} else {
    	//echo "Error: Invalid course ID.";
		echo '<div class="alert">' . 'Invalid course ID.' . '</div>';
	}
	
}

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
        <!--<input type="submit" value="Enroll"> <br>-->
		<button type="submit" name="submit">Enroll </button>
    </form>
    <a href="index.php">Return</a> <br>
</body>

<style>
	.alert {border:1px solid #bbb; padding:5px; margin:10px 0px; background:#ec7063;}
	.msg {border:1px solid #bbb; padding:5px; margin:10px 0px; background:#58d68d;}
</style>
</html>
