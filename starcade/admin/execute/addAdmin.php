<?php

session_start();
include '../../php/connection.php';

$username = "starcade";
$password = "starcade";
$email = "staircasec2@gmail.com";



$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$sql = "INSERT INTO admin (Username, Password, Email) VALUES (?, ?, ?)";

if($stmt = $conn->prepare($sql)){
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    if ($stmt->execute()) {
        echo "Admin added successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Error: " . $conn->error;
}

$conn->close(); 