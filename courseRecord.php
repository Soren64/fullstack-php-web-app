<?php
require 'config.php';
if(!empty($_SESSION["email"])){
    $email = $_SESSION["email"];
    $instructorQuery = mysqli_query($connection, "SELECT * FROM instructor WHERE email = '$email'");
    $row = mysqli_fetch_assoc($instructorQuery);
    $currentId = $row["instructor_id"];

    $sql = "SELECT MAX(year) AS max_year, semester
        FROM section
        GROUP BY year
        ORDER BY year DESC, FIELD(semester, 'Spring', 'Summer', 'Fall', 'Winter')
        LIMIT 1";
    $result = mysqli_query($connection, $sql);
    $currRow = $result->fetch_assoc();
    $curSem = $currRow["semester"];
    $curYear = $currRow["max_year"];

    $sectionQuery = mysqli_query($connection, "SELECT * FROM section WHERE instructor_id = '$currentId'");
    $pastSecQuery = mysqli_query($connection, "SELECT * FROM section WHERE instructor_id = '$currentId'");

    $sectionRow = mysqli_fetch_assoc($sectionQuery);

    //$currentQuery = mysqli_query($connection, "SELECT * FROM section WHERE instructor_id = '$currentId' AND semester = 'Spring' AND year = '2024'");
    $currentQuery = mysqli_query($connection, "SELECT * FROM section WHERE instructor_id = '$currentId' AND semester = '$curSem' AND year = '$curYear'");

    $pastQuery = mysqli_query($connection, "SELECT * FROM section WHERE instructor_id = '$currentId' AND semester != '$curSem' AND year <= '$curYear'");

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
        <!--Doesn't seem to be working without a valid past course-->
        <h3> Current Students: </h3>
        <?php
        while ($rslt = mysqli_fetch_assoc($sectionQuery)){
            if ($rslt["semester"] == "Spring" && $rslt["year"] == "2024"){
                $cid = $rslt["course_id"];
                $sid = $rslt["section_id"];
                $sem = $rslt["semester"];
                $year = $rslt["year"];
                
                $query = mysqli_query($connection, "SELECT * FROM take WHERE semester = '$sem' AND year = '$year' AND course_id = '$cid' AND section_id = '$sid'");
                while ($new = mysqli_fetch_assoc($query)){
                    $sid = $new["student_id"];
                    $newQuery = mysqli_query($connection, "SELECT * FROM student WHERE student_id = '$sid'");
                    while ($final = mysqli_fetch_assoc($newQuery)){
                        echo $final["name"] . "<br />";
                    }
                }
            }
        }
        ?>
        <h3> Past Students: </h3>
        <?php
        while ($rslt = mysqli_fetch_assoc($pastSecQuery)){
            if ($rslt["semester"] != "Spring" AND $rslt["year"] != "2024") {
                $cid = $rslt["course_id"];
                $sid = $rslt["section_id"];
                $sem = $rslt["semester"];
                $year = $rslt["year"];
                $query = mysqli_query($connection, "SELECT * FROM take WHERE semester = '$sem' AND year = '$year' AND course_id = '$cid' AND section_id = '$sid'");
                while ($new = mysqli_fetch_assoc($query)){
                    $sid = $new["student_id"];
                    $newQuery = mysqli_query($connection, "SELECT * FROM student WHERE student_id = '$sid'");
                    while ($final = mysqli_fetch_assoc($newQuery)){
                        //echo $cid . "\x20\x20\x20" . $sem . "\x20\x20\x20" . $year . "\x20\x20\x20" . $final["name"] . "\x20\x20\x20" . $new["grade"] . "<br />";
                        echo $cid . str_repeat('&nbsp;', 5) . $sem . str_repeat('&nbsp;', 5) . $year . str_repeat('&nbsp;', 5) . $final["name"] . str_repeat('&nbsp;', 5) . $new["grade"] . "<br />"; 
                    }
                }
            }
        }
        ?>

        <br/>
        
        <a href="instructIndex.php"> Return </a> <br>
        
        <a href="logout.php"> Logout </a> <br>
    </body>
</html>