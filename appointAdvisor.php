<?php
    require 'config.php';
    /*
    if(!empty($_SESSION["email"])){
        $email = $_SESSION["email"];
        $studentResult = mysqli_query($connection, "SELECT * FROM account WHERE email = '$email'");
        $row = mysqli_fetch_assoc($studentResult);
    }
    else {
        header("Location: login.php");
    }
    */
    if(isset($_POST["submit"])){

        $studentName = $_POST["student"];
        $advisorId = $_POST["advisor"];
        $start = $_POST["start"];
        $end = $_POST["end"];

        //check that student is a PhD student
        $sQuery = mysqli_query($connection, "SELECT * FROM student WHERE name = '$studentName'");
        $sRow = mysqli_fetch_assoc($sQuery);
        $sId = $sRow["student_id"];
        $phdQuery = mysqli_query($connection, "SELECT * FROM PhD WHERE student_id = '$sId'");

        // check that advisor isn't advising too many other students
        $aQuery = mysqli_query($connection, "SELECT * FROM advise WHERE instructor_id = '$advisorId'");

        // check that advisor isn't already advising the student
        $redun = mysqli_query($connection, "SELECT * FROM advise WHERE student_id = '$sId'");
        $redunRow = mysqli_fetch_assoc($redun);
        //var_dump($redunRow["instructor_id"]);
        //var_dump(mysqli_num_rows($aQuery));
        //var_dump($redunRow["instructor_id"] == $advisorId);
        if(mysqli_num_rows($phdQuery) == 0){ 
            echo "<script> alert('Student is not a PhD student'); </script>";
            header("Location: appointAdvisor.php");

        }
        else if (mysqli_num_rows($aQuery) >= 2){
            echo "<script> alert('Advisor is already assigned to two students')";
            header("Location: appointAdvisor.php");

        }
        else if ($redunRow["instructor_id"] == $advisorId){
            echo "<script> alert('Advisor is already assigned to this student')";
            header("Location: appointAdvisor.php");
        }
        else if (mysqli_num_rows($phdQuery) > 0 && mysqli_num_rows($aQuery) < 2 && $redunRow["instructor_id"] != $advisorId){
            if (empty($end)){
                $insertAdvisor = "INSERT INTO advise VALUES('$advisorId', '$sId', '$start', NULL)";
                mysqli_query($connection, $insertAdvisor);
                echo "<script> alert('Advisor assigned successfully')";
                header("Location: instructIndex.php");        }
            else if (!empty($end)){
                $insertAdvisor = "INSERT INTO advise VALUES('$advisorId','$sId','$start', '$end')";
                mysqli_query($connection, $insertAdvisor);
                echo "<script> alert('Advisor assigned successfully')";
                header("Location: instructIndex.php");
            }    
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

        <h3> Appoint Advisors: </h3>
        <form class="" action="" method = "post" autocomplete="off">
            <label for="student"> Student: </label>
            <input type="text" name="student" id="student" required> <br>

            <label for="advisor"> ID of the advisor to be assigned: </label>
            <input type="text" name="advisor" id="advisor" required> <br>

            <label for="start"> Start Date: </label>
            <input type="date" name="start" id="start" required> <br>

            <label for="end"> End Date (optional): </label>
            <input type="date" name="end" id="end" > <br>



            <button type="submit" name="submit">Submit </button>
        </form>
        <br>
        
        
        
        <a href="logout.php"> Logout </a> <br>

    </body>
</html>