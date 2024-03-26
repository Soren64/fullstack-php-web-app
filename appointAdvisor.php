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
            echo '<div class="alert">' . 'Student ' . $studentName . ' is not a PhD student' . '</div>';
            //echo "<script> alert('Student is not a PhD student'); </script>";
            //header("Location: appointAdvisor.php");

        }
        else if (mysqli_num_rows($aQuery) >= 2){
            echo '<div class="alert">' . 'Advisor with ID ' . $advisorId . ' is already assigned to two students' . '</div>';
            //echo "<script> alert('Advisor is already assigned to two students')";
            //header("Location: appointAdvisor.php");

        }
        else if ($redunRow["instructor_id"] == $advisorId){ // Might be an error with this check
            echo '<div class="alert">' . 'Advisor with ID ' . $advisorId . ' is already assigned to this student' . '</div>';
            //echo "<script> alert('Advisor is already assigned to this student')";
            //header("Location: appointAdvisor.php");
        }
        else if (mysqli_num_rows($phdQuery) > 0 && mysqli_num_rows($aQuery) < 2 && $redunRow["instructor_id"] != $advisorId){
            if (empty($end)){
                $insertAdvisor = "INSERT INTO advise VALUES('$advisorId', '$sId', '$start', NULL)";
                mysqli_query($connection, $insertAdvisor);
                echo '<div class="msg">' . 'Advisor with ID ' . $advisorId . ' successfully assigned to student ' . $studentName . '</div>';
                //echo "<script> alert('Advisor assigned successfully')";
                //header("Location: instructIndex.php");        
            }
            else if (!empty($end)){
                $insertAdvisor = "INSERT INTO advise VALUES('$advisorId','$sId','$start', '$end')";
                mysqli_query($connection, $insertAdvisor);
                echo '<div class="msg">' . 'Advisor with ID ' . $advisorId . ' successfully assigned to student ' . $studentName . '</div>';
                //echo "<script> alert('Advisor assigned successfully')";
                //header("Location: instructIndex.php");
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
        
        
        <?php  
            if($_SESSION["email"] == 'admin@uml.edu')
            {
                $return = "adminIndex.php";
            }
            else
            {
                $return = "instructIndex.php";
            }
        ?>
        <a href=<?=$return?>> Go Back </a> <br>
        
        <a href="logout.php"> Logout </a> <br>

    </body>

    <style>
    	.alert {border:1px solid #bbb; padding:5px; margin:10px 0px; background:#ec7063;}
		.msg {border:1px solid #bbb; padding:5px; margin:10px 0px; background:#58d68d;}
	</style>
</html>