<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "p";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection properly
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
