<?php

$servername = "db.luddy.indiana.edu";
$username = "i494f23_team30";
$password = "my+sql=i494f23_team30";
$dbname = "i494f23_team30";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
?>
