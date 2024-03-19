<?php
require 'config.php';
if(isset($_POST["submit"])){

    $course = $_POST["course"];
    $section = $_POST["section"];
    $semester = $_POST["semester"];
    $year = $_POST["year"];
    $instId = $_POST["instructor"];
    $class = $_POST["class"];
    $timeslot = $_POST["timeslot"];
    $day = $_POST["day"];

    $reqs = true;

    //check that a valid instructor ID is given
    $query = mysqli_query($connection, "SELECT * FROM instructor WHERE instructor_id = '$instId'");
    if (mysqli_num_rows($query) == 0){
        $reqs = false;
        echo "<script> alert('Invalid Instructor ID'); </script>";
    }

    //check that there's no more than 2 sections per timeslot
    $timeslotQuery = mysqli_query($connection, "SELECT * FROM section WHERE semester = '$semester' AND year = '$year' AND time_slot_id = '$timeslot'");
    if (mysqli_num_rows($timeslotQuery) >= 2){
        $reqs = false;
        echo "<script> alert('Two sections are already assigned to this timeslot'); </script>";
    }

    //check that the section for the course doesn't exist already
    $secQuery = mysqli_query($connection, "SELECT * FROM section WHERE semester = '$semester' AND year = '$year' AND section_id = '$section'");
    if (mysqli_num_rows($secQuery) > 0){
        $reqs = false;
        echo "<script> alert('This section already exists'); </script>";
    }

    //check that the instructor doesn't teach more than 2 sections in one semester
    $instQuery = mysqli_query($connection, "SELECT * FROM section WHERE semester = '$semester' AND year = '$year' AND instructor_id = '$instId'");
    if (mysqli_num_rows($instQuery) > 2){
        $reqs = false;
        echo "<script> alert('This instructor already teaches two sections'); </script>";
    }

    //check that any instructor that teaches two sections are in consecutive timeslots
    $iQuery = mysqli_query($connection, "SELECT * FROM section WHERE semester = '$semester' AND year = '$year' AND instructor_id = '$instId'");
    $iRow = mysqli_fetch_assoc($iQuery);
    //case for instructor teaching one section
    if (mysqli_num_rows($iQuery) == 1){
        $tQuery = mysqli_query($connection, "SELECT * FROM section WHERE semester = '$semester' AND year = '$year' AND instructor_id = '$instId'");
        $tRow = mysqli_fetch_assoc($tQuery);
        $curTimeslot = $tRow["time_slot_id"];
        $curDay = $tRow["day"];
        //if the new timeslot and current timeslot are on different days, reject
        if ($curDay != $day){
            $reqs = false;
            echo "<script> alert('The instructor must have two consecutive timeslots'); </script>";
        }
        //if the timeslot are on the same day but not consecutive, reject
        else if ((ord(substr($timeslot))+ 1) != ord($curTimeslot)){
            $reqs = false;
            echo "<script> alert('The instructor must have two consecutive timeslots'); </script>";
        }
        //if the timeslots are consecutive on the same day, accept and insert into section
        else if((ord(substr($timeslot, 2))+ 1) == ord(substr($curTimeslot)) && $reqs){
            $insert = "INSERT INTO section VALUES('$course','$section','$semester','$year', '$instId','$class','$timeslot')";
            $insertQuery = mysqli_query($connection, $insert);
            echo "<script> alert('Section created successfully'); </script>";
        }
    }
    else if ($reqs){
        $insert = "INSERT INTO section VALUES('$course','$section','$semester','$year', '$instId','$class','$timeslot')";
        $insertQuery = mysqli_query($connection, $insert);
        echo "<script> alert('Section created successfully'); </script>";
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

    <h3> Create New Section: </h3>
    <form class="" action="" method = "post" autocomplete="off">
        <label for="course"> Course ID: </label>
        <input type="text" name="course" id="course" required> <br>

        <label for="section"> New Section ID: </label>
        <input type="text" name="section" id="section" required> <br>

        <label for="semester"> Semester: </label>
        <input type="semester" name="semester" id="semester" required> <br>

        <label for="year"> Year: </label>
        <input type="year" name="year" id="year" required> <br>

        <label for="instructor"> Instructor ID: </label>
        <input type="instructor" name="instructor" id="instructor" required> <br>

        <label for="class"> Classroom ID: </label>
        <input type="class" name="class" id="class"> <br>

        <label for="timeslot"> Time Slot ID: </label>
        <input type="timeslot" name="timeslot" id="timeslot" > <br>

        <label for="day"> Day: </label>
        <input type="day" name="day" id="day" > <br>





        <button type="submit" name="submit">Submit </button>
    </form>
    <br>



<a href="adminIndex.php"> Return </a> <br>

<a href="logout.php"> Logout </a> <br>

</body>
</html>