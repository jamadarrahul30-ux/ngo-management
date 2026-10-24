<?php

$host = "localhost";
$username = "root";
$password = "";
$database = "ngo";

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    //die("Database connection failed: " . mysqli_connect_error());
    echo "Warning : Database is Offline , but page is allowed to load!<br>";
}

?>