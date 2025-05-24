<?php
session_start();
include('../../php/connection.php');
include('../../php/mail-function.php');

$_SESSION['signup'] = true;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_POST['token']) || $_POST['token'] !== $_SESSION['form_token']) {
        $_SESSION['error'] = "Invalid or duplicate submission.";
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }
    unset($_SESSION['form_token']);

    $username = trim($_POST['username']);
    $email = trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check for empty fields
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $errors[] = "All fields are required.";
    }

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Validate password
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        $errors[] = "Password must be at least 8 characters long and include both letters and numbers.";
    }

    // Check if username or email already exists
    $checkSql = "SELECT 1 FROM admin WHERE username = ? OR email = ?";
    if ($checkStmt = $conn->prepare($checkSql)) {
        $checkStmt->bind_param("ss", $username, $email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            $errors[] = "Username or email already exists.";
        }

        $checkStmt->close();
    } else {
        $_SESSION['error'] = "Database error: " . $conn->error;
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // If any errors occurred, redirect back with message
    if (!empty($errors)) {
        $_SESSION['error_message'] = implode(" ", $errors);
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit();
    }

    // Proceed with insert
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO admin (username, password, email) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sss", $username, $hashed_password, $email);

        if ($stmt->execute()) {
            sendMail($email);
            header("Location: ../../verifyEmail.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}
?>
