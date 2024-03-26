
<?php
require 'config.php';

if (isset($_POST['submit'])) {
    $course_id = $_POST['course_id'];
    $section_id = $_POST['section_id'];
    $grader_id = $_POST['grader_id'];
    $year = $_POST['year'];
    $semester = $_POST['semester'];

    // Validate the grader's grade
    $grade_query = "SELECT grade FROM take WHERE course_id = '$course_id' AND student_id = '$grader_id'";
    $grade_result = mysqli_query($connection, $grade_query);
    $grade_row = mysqli_fetch_assoc($grade_result);

    if ($grade_row['grade'] === 'A' || $grade_row['grade'] === 'A+' || $grade_row['grade'] === 'A-') {
        // Check roster size
        $roster_query = "SELECT COUNT(*) AS roster_size FROM take WHERE section_id = '$section_id'";
        $roster_result = mysqli_query($connection, $roster_query);
        $roster_row = mysqli_fetch_assoc($roster_result);
        $roster_size = (int)$roster_row['roster_size'];

        if ($roster_size >= 5 && $roster_size <= 10) {
            // Check if the student is an undergrad
            $undergrad_query = "SELECT * FROM undergraduate WHERE student_id = '$grader_id'";
            $undergrad_result = mysqli_query($connection, $undergrad_query);

            if (mysqli_num_rows($undergrad_result) > 0) {
                // Student is an undergrad, add them to undergraduateGrader table
                $insert_query = "INSERT INTO undergraduateGrader (student_id, course_id, section_id, semester, year) VALUES ('$grader_id', '$course_id', '$section_id', '$semester', '$year')";
                mysqli_query($connection, $insert_query);
                echo '<div class="msg"> Undergrad grader assigned successfully! </div>';

            } else {
                // Check if the student is a master
                $master_query = "SELECT * FROM master WHERE student_id = '$grader_id'";
                $master_result = mysqli_query($connection, $master_query);
                
                if (mysqli_num_rows($master_result) > 0) {
                    $insert_query = "INSERT INTO masterGrader (student_id, course_id, section_id, semester, year) VALUES ('$grader_id', '$course_id', '$section_id', '$semester', '$year')";
                    mysqli_query($connection, $insert_query);
                    echo '<div class="msg">' . 'Master grader assigned successfully!' . '</div>';

                } else {
                    echo '<div class="alert">' . 'Student must be an undergrad or a master.' . '</div>';

                }
            }
        } else {
            echo '<div class="alert">' . 'Class roster size must be between 5 and 10 (inclusive).' . '</div>';
        }
    } else {
        echo '<div class="alert">' . 'Grader must have received a grade of \'A\' or \'A-\' in the course.' . '</div>';
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Assign Grader</title>
    </head>

    <body>
        <h1> Appoint Graders: </h1>
        <form class="" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
            <label for="course_id">Course ID:</label>
            <input type="text" name="course_id" id="course_id">

            <label for="section_id">Section ID:</label>
            <input type="text" name="section_id" id="section_id"><br><br>

            <label for="grader_id">Grader ID (Student ID):</label>
            <input type="text" name="grader_id" id="grader_id"><br><br>

            <label for="year">Year:</label>
            <input type="text" name="year" id="year">

            <label for="semester">Semester:</label>
            <input type="text" name="semester" id="semester"><br>

            <button type="submit" name="submit">Assign Grader</button><br>
        </form>

        <a href="adminIndex.php"> Go Back </a> <br>
    </body>

    <style>
        .alert {border:1px solid #bbb; padding:5px; margin:10px 0px; background:#ec7063;}
        .msg {border:1px solid #bbb; padding:5px; margin:10px 0px; background:#58d68d;}
    </style>
</html>
