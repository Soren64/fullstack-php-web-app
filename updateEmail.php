<?php
    require 'config.php';
    if(isset($_POST["submit"])){
        $newEmail = $_POST["newemail"];
        $oldEmail = $_POST["oldemail"];


        $query = mysqli_query($connection, "SELECT * FROM student WHERE email = '$newEmail'");
        if (mysqli_num_rows($query) > 0){
            echo '<div class="alert">' . 'Email already taken' . '</div>';
            //echo "<script> alert('Email already taken'); </script>";
        }
        else {
            $updateStudentQuery = mysqli_query($connection, "UPDATE student SET email = '$newEmail' WHERE email = '$oldEmail'");
            //$updateLoginQuery = mysqli_query($connection, "UPDATE login SET email = '$newEmail' WHERE email = '$oldEmail'");
            $updateAccountQuery = mysqli_query($connection, "UPDATE account SET email = '$newEmail' WHERE email = '$oldEmail'");
            echo '<div class="msg">' . 'Email changed successfully' . '</div>';
            //echo "<script> alert('Email changed successfully'); </script>";
        }
    }
?>

<!DOCTYPE html>
<html>
    <body>

        <form class="" action="" method = "post" autocomplete="off">
            <label for="oldemail"> Current Email: </label>
            <input type="text" name="oldemail" id="oldemail" required value=""> <br>


            <label for="newemail"> New Email: </label>
            <input type="text" name="newemail" id="newemail" required value=""> <br>

            <button type="submit" name="submit">Submit </button>
        </form> <br>

        <a href="index.php"> Return </a>

    </body>

    <style>
    	.alert {border:1px solid #bbb; padding:5px; margin:10px 0px; background:#ec7063;}
		.msg {border:1px solid #bbb; padding:5px; margin:10px 0px; background:#58d68d;}
	</style>
</html>