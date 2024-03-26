<?php
    require 'config.php';
    if(!empty($_SESSION["email"])){
        //$email = $_SESSION["email"];
        //$studentResult = mysqli_query($connection, "SELECT * FROM instructor WHERE email = '$email'");
        //$row = mysqli_fetch_assoc($studentResult);
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

    <h1> Welcome Admin </h1>



    <a href="appointGrader.php"> Appoint Graders </a> <br>
    <a href="appointTA.php"> Appoint Teaching Assistants </a> <br>
    <a href="appointAdvisor.php"> Appoint PhD Student Advisors </a> <br>
    <a href="createSection.php"> Create New Section </a> <br>
    <a href="logout.php"> Logout </a> <br>

    </body>
</html>
