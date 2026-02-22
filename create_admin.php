<?php
require "./includes/db_connect.php";

$name = "Admin";
$email = "admin@gmail.com";
$password = "Admin@123";   // choose this yourself
$role = "admin";

$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "INSERT INTO users (name, email, password, role)
   VALUES (?, ?, ?, ?)"
);

$stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);
$stmt->execute();

echo "Admin created successfully.<br>";
echo "Email: $email<br>";
echo "Password: $password";
?>