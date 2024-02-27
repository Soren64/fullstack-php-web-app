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
        if ($password == $row["password"]){
            $_SESSION["login"] = true;
            $_SESSION["email"] = $row["email"];
            header("Location: index.php");
        }
        else {
            echo "<script> alert('Wrong password'); </script>";
        }
    }
    else {
        echo "<script> alert('User not registered'); </script>";
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


</html>