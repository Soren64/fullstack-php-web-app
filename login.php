<?php
    require 'config.php';
    if(!empty($_SESSION["id"])){
        header("Location: index.php");
    }
    if(isset($_POST["submit"])){
        $email = $_POST["email"];
        $password = $_POST["password"];
        $result = mysqli_query($connection, "SELECT * FROM account WHERE email = '$email'");
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) > 0){
            if ($password == $row["password"] && $row["type"] == 'student'){
                $_SESSION["login"] = true;
                $_SESSION["email"] = $row["email"];
                header("Location: index.php");
            }
            else if ($password == $row["password"] && $row["type"] == 'instructor'){
                $_SESSION["login"] = true;
                $_SESSION["email"] = $row["email"];
                header("Location: instructIndex.php");
            }
            else if ($password == $row["password"] && $row["type"] == 'admin'){
                $_SESSION["login"] = true;
                $_SESSION["email"] = $row["email"];
                header("Location: adminIndex.php");
            }
            else {
                echo '<div class="alert">' . 'Wrong password' . '</div>';
                //echo "<script> alert('Wrong password'); </script>";
            }
        }
        else {
            echo '<div class="alert">' . 'User not registered' . '</div>';
            //echo "<script> alert('User not registered'); </script>";
        }
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title> Login </title>
    </head>

    <body>
        <h2> Login </h2>
        <form class="" action="" method = "post" autocomplete="off">
            <label for="email"> Email: </label>
            <input type="text" name="email" id="email" required value=""> <br>

            <label for="password"> Password: </label>
            <input type="text" name="password" id="password" required value=""> <br>

            <button type="submit" name="submit">Login </button>
        </form>
        <br>
        <a href="register.php">Registration</a>

    </body>

    <style>
    	.alert {border:1px solid #bbb; padding:5px; margin:10px 0px; background:#ec7063;}
	</style>
</html>