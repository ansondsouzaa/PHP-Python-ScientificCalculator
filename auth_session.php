<?php
session_start();
if (!isset($_SESSION["email"])) {
    header("Location: login.php");
    exit();
}

// Adding user's name in session.
require('db.php');
$email = $_SESSION["email"];
$query = "SELECT name FROM `users` WHERE email='$email'";
$result = mysqli_query($con, $query);
$row = mysqli_fetch_assoc($result);
$_SESSION['name'] = $row['name'];
?>