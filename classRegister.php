<!DOCTYPE html>
<html>
<head>
    <title>Course Enrollment</title>
</head>
<body>
    <?php
    require 'config.php';
    
    //Derive the current semester
    $sql = "SELECT MAX(year) AS max_year, semester
            FROM section
            GROUP BY year
            ORDER BY year DESC, FIELD(semester, 'Spring', 'Summer', 'Fall', 'Winter')
            LIMIT 1";
    $result = mysqli_query($connection, $sql);
    $row = $result->fetch_assoc();
    $currentSemester = $row["semester"] . " " . $row["max_year"];
    ?>
    <h1>Course Enrollment</h1>
    <p>Current Semester: <?php echo $currentSemester; ?></p>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="course">Select a Course:</label>
        <select id="course" name="course">
            <?php
            //Fetch courses
            $coursesQuery = "SELECT course_id, course_name FROM course";
            $result = mysqli_query($connection, $coursesQuery);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["course_id"] . "'>" . $row["course_name"] . "</option>";
                }
            }

            ?>
        </select>
        <br>
        <label for="section">Select a Section:</label>
        <select id="section" name="section">
            <?php
            $SelectedClass = $_POST['course'];
            $refID = mysqli_query($connection, "SELECT course_id FROM course WHERE course_name = '$SelectedClass'");
            $courseId = mysqli_fetch_assoc($refID)['course_id'];          

            
            //Fetch sections for the current semester
            $sql = "SELECT section_id, course_id FROM section WHERE year = " . $row["max_year"] . " AND semester = '" . $row["semester"] . "' AND course_id = '$courseId'";
            $result = mysqli_query($connection, $sql);
   

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["section_id"] . "'>" . $row["course_id"] . "</option>";
                }
            }

            ?>
        </select>
        <br>
        <?php
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
        ?>
        <input type="submit" value="Enroll">
        <!--<a href="index.php"> Return </a> <br>-->
    </form>

    <!--<a href="index.php"> Go Back </a> <br>-->

</body>

<style>
    .alert {border:1px solid #bbb; padding:5px; margin:10px 0px; background:#ec7063;}
	.msg {border:1px solid #bbb; padding:5px; margin:10px 0px; background:#58d68d;}
</style>
</html>

