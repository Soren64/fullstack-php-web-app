<?php
require 'config.php';
if(!empty($_SESSION["email"])){
    $email = $_SESSION["email"];
    $instructorQuery = mysqli_query($connection, "SELECT * FROM instructor WHERE email = '$email'");
    $row = mysqli_fetch_assoc($instructorQuery);
    $currentId = $row["instructor_id"];

    $sectionQuery = mysqli_query($connection, "SELECT * FROM section WHERE instructor_id = '$currentId'");
    $sectionRow = mysqli_fetch_assoc($sectionQuery);

    $currentQuery = mysqli_query($connection, "SELECT * FROM section WHERE instructor_id = '$currentId' AND semester = 'Spring' AND year = '2024'");
    $pastQuery = mysqli_query($connection, "SELECT * FROM section WHERE instructor_id = '$currentId' AND semester != 'Spring' AND year < '2024'");

    //$currentStudentQuery = mysqli_query($connection, "SELECT * FROM take WHERE semester = 'Spring' AND year = '2024' AND ");


}
else {
    header("Location: index.php");
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Index</title>
</head>
<body>

<h1> Your Courses: </h1>

<h3> Current Courses:  </h3>
<?php
while ($rslt = mysqli_fetch_array($currentQuery)){
    echo $rslt["course_id"] . "<br />";
}
?>

<h3> Past Courses:  </h3>
<?php
while ($rslt = mysqli_fetch_array($pastQuery)){
    echo $rslt["course_id"] . "<br />";
}
?>

<h3> Current Students: </h3>
<?php
while ($rslt = mysqli_fetch_assoc($sectionQuery)){
    if ($rslt["semester"] == "Spring" && $rslt["year"] == "2024"){
        $cid = $rslt["course_id"];
        $sid = $rslt["section_id"];
        $sem = $rslt["semester"];
        $year = $rslt["year"];
        $query = mysqli_query($connection, "SELECT * FROM take WHERE semester = $sem AND year = $year AND course_id = $cid AND section_id = $sid");
        while ($new = mysqli_fetch_assoc($query)){
            $sid = $new["student_id"];
            $newQuery = mysqli_query($connection, "SELECT * FROM student WHERE student_id = $sid");
            while ($final = mysqli_fetch_assoc($newQuery)){
                echo $final["name"] . "<br />";
            }
        }
    }
}

?>

<h3> Past Students: </h3>
<?php
while ($rslt = mysqli_fetch_assoc($sectionQuery)){
    $cid = $rslt["course_id"];
    $sid = $rslt["section_id"];
    $sem = $rslt["semester"];
    $year = $rslt["year"];
    $query = mysqli_query($connection, "SELECT * FROM take WHERE semester = $sem AND year = $year AND course_id = $cid AND semester_id = $sid");
    while ($new = mysqli_fetch_assoc($query)){
        $sid = $new["student_id"];
        $newQuery = mysqli_query($connection, "SELECT * FROM student WHERE student_id = $sid");
        while ($final = mysqli_fetch_assoc($newQuery)){
                //echo $final["name"] . "<br />";
                echo $cid . "\x20\x20\x20" . $sem . "\x20\x20\x20" . $year . "\x20\x20\x20" . $final["name"] . "\x20\x20\x20" . $new["grade"] . "<br />"; 
        }
    }
}
    

?>



<a href="instructIndex.php"> Return </a> <br>

<a href="logout.php"> Logout </a> <br>

</body>
</html>