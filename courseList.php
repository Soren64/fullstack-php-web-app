<?php
require 'config.php';
if(!empty($_SESSION["email"])){
    $email = $_SESSION["email"];
    //$result = mysqli_query($connection, "SELECT * FROM login WHERE id = $id");
    $studentQuery = mysqli_query($connection, "SELECT * FROM student WHERE email = '$email'");
    $row = mysqli_fetch_assoc($studentQuery);

    $currentId = $row["student_id"];

    $currentTakeQuery = mysqli_query($connection, "SELECT * FROM take WHERE student_id = '$currentId' AND semester = 'Spring' AND year = '2024'");
    $pastTakeQuery = mysqli_query($connection, "SELECT * FROM take WHERE student_id = '$currentId' AND semester != 'Spring' AND year < '2024'");
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

<h3> Cumulative GPA:  </h3>




<a href="index.php"> Return </a> <br>

<a href="logout.php"> Logout </a> <br>

</body>
</html>

