<?php
// Load variables from .env file
require 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$hostname = $_ENV['DB_HOST'];
$user = $_ENV['DB_USER'];
$password = "";
$database = "calculator_db";

$con = mysqli_connect($hostname, $user, $password, $database);
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

?>