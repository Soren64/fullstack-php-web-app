<?php
    require 'config.php';
    if(!empty($_SESSION["email"])){
        header("Location: index.php");
    }
    if(isset($_POST["submit"])){
        $name = $_POST["name"];
        $student_id = $_POST["studentid"];
        //$username = $_POST["username"];
        $email = $_POST["email"];
        $dept = $_POST["dept"];
        $password = $_POST["password"];
        $confirmPassword = $_POST["confirmpassword"];
        $duplicate = mysqli_query($connection, "SELECT * FROM account WHERE email = '$email'");
        if(mysqli_num_rows($duplicate) > 0){
            echo '<div class="alert">' . 'Email is already taken' . '</div>';
            //echo "<script> alert('Email is already taken'); </script>";
        }
        else {
            if($password == $confirmPassword){
                $accountQuery = "INSERT INTO account VALUES('$email','$password','student')";
                mysqli_query($connection, $accountQuery);
                //$query = "INSERT INTO login VALUES('$student_id','$name','$username','$email','$password')";
                //mysqli_query($connection, $query);
                //$deptQuery = "INSERT INTO department VALUES('$dept','')";
                $studentQuery = "INSERT INTO student VALUES('$student_id','$name','$email','$dept')";
                //mysqli_query($connection, $deptQuery);
                mysqli_query($connection, $studentQuery);
                echo '<div class="msg">' . 'Registration successful' . '</div>';
                //echo "<script> alert('Registration successful'); </script>";
            }
            else {
                echo '<div class="alert">' . 'Passwords do not match' . '</div>';
                //echo "<script> alert('Passwords do not match'); </script>";
            }
        }
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Registration</title>
    </head>

    <body>

    <h2> Registration </h2>
    <form class="" action="" method="post">

        <label for="name"> Name: </label>
        <input type="text" name="name" id="name" required value=""> <br>

        <label for="studentid"> Student ID: </label>
        <input type="text" name="studentid" id="studentid" required value=""> <br>

        <label for="dept"> Department: </label>
        <input type="text" name="dept" id="dept" required value=""> <br>

        <label for="email"> Email: </label>
        <input type="text" name="email" id="email" required value=""> <br>

        <label for="password"> Password: </label>
        <input type="text" name="password" id="password" required value=""> <br>

        <label for="confirmpassword"> Confirm Password: </label>
        <input type="text" name="confirmpassword" id="confirmpassword" required value=""> <br>

        <button type="submit" name="submit"> Register </button>
    </form>
    <br>

    <a href="login.php"> Login </a>

    </body>

    <style>
    	.alert {border:1px solid #bbb; padding:5px; margin:10px 0px; background:#ec7063;}
		.msg {border:1px solid #bbb; padding:5px; margin:10px 0px; background:#58d68d;}
	</style>
</html>