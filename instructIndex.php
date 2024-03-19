<?php
    require 'config.php';
    if(!empty($_SESSION["email"])){
        $email = $_SESSION["email"];
        //$result = mysqli_query($connection, "SELECT * FROM login WHERE id = $id");
        $studentResult = mysqli_query($connection, "SELECT * FROM instructor WHERE email = '$email'");
        $row = mysqli_fetch_assoc($studentResult);
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

        <h1> Welcome <?php echo $row["instructor_name"]; ?> </h1>

        <h3> Your email: <?php echo $row["email"] ?> </h3>
        <h3> Your instructor ID: <?php echo $row["instructor_id"] ?> </h3>
        <h3> Your department: <?php echo $row["dept_name"] ?> </h3>


        <a href="courseRecord.php"> View Course Records </a> <br>
        <a href="updateEmail.php"> Update Email Address </a> <br>
        <a href="appointAdvisor.php"> Appoint PhD Student Advisors </a> <br>
        <a href="logout.php"> Logout </a> <br>

    </body>
</html>