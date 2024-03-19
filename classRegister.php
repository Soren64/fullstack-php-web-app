<!DOCTYPE html>
<html>
<head>
    <title>Course Enrollment</title>
</head>
<body>
    <?php
    //Derive the current semester
    $sql = "SELECT MAX(year) AS max_year, semester
            FROM section
            GROUP BY year
            ORDER BY year DESC, FIELD(semester, 'Spring', 'Summer', 'Fall', 'Winter')
            LIMIT 1";
    $result = mysqli_query($connection, $sql);
    $row = $result->fetch_assoc();
    $currentSemester = "Year " . $row["max_year"] . ", Semester " . $row["semester"];
    ?>
    <h1>Course Enrollment</h1>
    <p>Current Semester: <?php echo $currentSemester; ?></p>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="course">Select a Course:</label>
        <select id="course" name="course">
            <?php
            //Fetch courses
            $sql = "SELECT course_id, course_name FROM course";
            $result = mysqli_query($connection, $sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
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
            $sql = "SELECT section_id, course_id FROM section WHERE year = " . $row["max_year"] . " AND semester = '" . $row["semester"] . "' AND course_id = $courseId";
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

            //Obtain userID
	    $userEmail = $_SESSION["email"];
	    $userID = mysqli_query($connection, "SELECT student_id FROM student WHERE email = '$userEmail'");
	    

	    $grade = null;
            $signup = mysqli_query($connection, "INSERT INTO take (student_id, course_id, section_id, semester, year, grade) VALUES ($userId, $courseId, $sectionId, " . $row["max_year"] . ", '" . $row["semester"] . "', $grade)");

            
            if ($signup) {
                echo "<p>Enrolled successfully.</p>";
            } else {
                echo "<script> alert('Error: Unable to register for course.'); </script>";
            }
        }
        ?>
        <input type="submit" value="Enroll">
	<a href="instructIndex.php"> Return </a> <br>    
    </form>
</body>
</html>

