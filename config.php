<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */

$dbHost = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "barrio-liwanag"; //change niyo ito according sa name ng database niyo

$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);
if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
?>