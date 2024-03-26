<?php
    require 'config.php';
    if(!empty($_SESSION["email"])){
        $email = $_SESSION["email"];
        $studentQuery = mysqli_query($connection, "SELECT * FROM student WHERE email = '$email'");
        $row = mysqli_fetch_assoc($studentQuery);

        $sql = "SELECT MAX(year) AS max_year, semester
            FROM section
            GROUP BY year
            ORDER BY year DESC, FIELD(semester, 'Spring', 'Summer', 'Fall', 'Winter')
            LIMIT 1";
        $result = mysqli_query($connection, $sql);
        $currRow = $result->fetch_assoc();
        $curSem = $currRow["semester"];
        $curYear = $currRow["max_year"];

        $currentId = $row["student_id"];

        $gpaQuery = mysqli_query($connection, "SELECT * FROM take WHERE student_id = '$currentId' AND grade IS NOT NULL"); // added null check to exclude currently taken courses

        $currentTakeQuery = mysqli_query($connection, "SELECT * FROM take WHERE student_id = '$currentId' AND semester = '$curSem' AND year = '$curYear'");

        $pastTakeQuery = mysqli_query($connection, "SELECT * FROM take WHERE student_id = '$currentId' AND semester != '$curSem' AND year <= '$curYear'");

        $undergradQuery = mysqli_query($connection, "SELECT * FROM undergraduate WHERE student_id = '$currentId'");
        $undergradRow = mysqli_fetch_assoc($undergradQuery);
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
        <h1> Your Academics: </h1>
    
        <h3> Courses currently being taken:  </h3>
        <?php
            while ($rslt = mysqli_fetch_array($currentTakeQuery)){
                echo $rslt["course_id"] . "<br />";
            }
        ?>
        <h3> Courses taken:  </h3>
        <?php
            while ($rslt = mysqli_fetch_array($pastTakeQuery)){
                echo $rslt["course_id"] . "<br />";
            }
        ?>
        
        <h3> Total Credits: <?php echo $undergradRow["total_credits"] ?> </h3> 

        <?php
            $gradePoints = 0;
            $gradePointsArr = array();
            while ($rslt = mysqli_fetch_array($gpaQuery)){
                array_push($gradePointsArr, $rslt["grade"]);
            }
            for ($i = 0; $i < count($gradePointsArr); $i++){
                if ($gradePointsArr[$i] == "A+"){
                    $gradePoints += 4.0;
                }
                else if ($gradePointsArr[$i] == "A"){
                    $gradePoints += 4.0;
                }
                else if ($gradePointsArr[$i] == "A-"){
                    $gradePoints += 3.7;
                }
                else if ($gradePointsArr[$i] == "B+"){
                    $gradePoints += 3.3;
                }
                else if ($gradePointsArr[$i] == "B"){
                    $gradePoints += 3.0;
                }
                else if ($gradePointsArr[$i] == "B-"){
                    $gradePoints += 2.7;
                }
                else if ($gradePointsArr[$i] == "C+"){
                    $gradePoints += 2.3;
                }
                else if ($gradePointsArr[$i] == "C"){
                    $gradePoints += 2.0;
                }
                else if ($gradePointsArr[$i] == "C-"){
                    $gradePoints += 1.7;
                }
                else if ($gradePointsArr[$i] == "D+"){
                    $gradePoints += 1.3;
                }
                else if ($gradePointsArr[$i] == "D"){
                    $gradePoints += 1.0;
                }
                else if ($gradePointsArr[$i] == "D-"){
                    $gradePoints += 0.7;
                }
                else if ($gradePointsArr[$i] == "F"){
                    $gradePoints += 0.0;
                }
            }

            $gpa = $gradePoints / count($gradePointsArr);
        ?>
        
        <h3> Cumulative GPA: <?php echo $gpa . "<br />"?> </h3>
        
        
        
        <a href="index.php"> Return </a> <br>
        
        <a href="logout.php"> Logout </a> <br>
    </body>
</html>
