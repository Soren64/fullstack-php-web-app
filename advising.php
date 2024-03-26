<?php
    require 'config.php';
    if(!empty($_SESSION["email"])){
        $email = $_SESSION["email"];

        $instructorQuery = mysqli_query($connection, "SELECT * FROM instructor WHERE email = '$email'");
        $instructorRow = mysqli_fetch_assoc($instructorQuery);
        $instId = $instructorRow["instructor_id"];

        $adviseeQuery = mysqli_query($connection, "SELECT * FROM advise WHERE instructor_id = '$instId'");

        
    }
    else {
        header("Location: login.php");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Index</title>
    </head>
    
    <body>
        
        <h1> Welcome <?php echo $instructorRow["instructor_name"]; ?> </h1>

        <h3> Advisees' Course History:  </h3>
        <?php
            while ($rslt = mysqli_fetch_assoc($adviseeQuery)){
                    $sid = $rslt["student_id"];
                    $query = mysqli_query($connection, "SELECT * FROM take WHERE student_id = '$sid'");
                    while ($new = mysqli_fetch_assoc($query)){
                        $secId = $new["section_id"];
                        $cid = $new["course_id"];
                        $sem = $new["semester"];
                        $year = $new["year"];
                        $newQuery = mysqli_query($connection, "SELECT * FROM student WHERE student_id = '$sid'");
                        while ($final = mysqli_fetch_assoc($newQuery)){
                                echo $cid . str_repeat('&nbsp;', 5) . $sem . str_repeat('&nbsp;', 5) . $year . str_repeat('&nbsp;', 5) . $final["name"]  . "<br />"; 
                
                        }
                    }
            }
        ?>

        <a href="updateEmail.php"> Update Advisees' Email Address </a> <br>
        <a href="instructIndex.php"> Return </a> <br>
        <a href="logout.php"> Logout </a> <br>

    </body>
</html>