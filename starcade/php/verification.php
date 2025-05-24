<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($_POST['token'] !== $_SESSION['form_token']) {
        $_SESSION['error'] = "Invalid or duplicate submission.";
        header("Location: " . $_SERVER['HTTP_REFERER']);         
        exit();
    }
    
    if(isset($_POST['code'])){
        $cleaned_code = trim($_POST['code']);
        if($cleaned_code == $_SESSION['verification_code']){
            unset($_SESSION['verification_code']);

            if (isset($_SESSION['signup'], $_SESSION['pending_user']) && $_SESSION['signup']) {
                $_SESSION['success_message'] = "Email verified successfully!";

                // Insert the user into the database

                    $user = $_SESSION['pending_user'];
                    $username = $user['username'];
                    $email = $user['email'];
                    $password  = $user['password'];

                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    
                    $sql = "INSERT INTO user (Username, Password, Email) VALUES (?, ?, ?)";

                    if($stmt = $conn->prepare($sql)){
                        $stmt->bind_param("sss", $username, $hashed_password, $email);

                        if ($stmt->execute()) {
                            echo "User added successfully!";
                            unset($_SESSION['form_token'], $_SESSION['pending_user'], $_SESSION['signup']);
                            $_SESSION['email'] = $email; 
                            $_SESSION['success_message'] = "Account created successfully! Please log in.";
                            header("Location: ../LoginForm.php");
                            exit();
                        } else {
                            echo "Error: " . $stmt->error;
                        }
                    } else {
                        echo "Error: " . $conn->error;
                    }

                    $conn->close();
            }else{
                unset($_SESSION['verification_code']);
                header("Location: ../changePassword.php");
                exit();
            }
        }else{
            $_SESSION['error_message'] = "Invalid Code.";
            header("Location: ../verifyEmail.php");
            exit();
        }
    } else {
        echo 'where the code at?';
    }
}
    