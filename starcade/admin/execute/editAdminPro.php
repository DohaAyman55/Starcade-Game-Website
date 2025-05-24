<?php
    include('../../php/connection.php');
    session_start();

    $username = $_SESSION['username'];
    $success_messages = [];
    $error_messages = [];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        
        if ($_POST['token'] !== $_SESSION['form_token']) {
            $_SESSION['error'] = "Invalid or duplicate submission.";
            header("Location: " . $_SERVER['HTTP_REFERER']);         
            exit();
        }
        unset($_SESSION['form_token']);

        if(isset($_POST['profile-pic'])){
            $value = $_POST['profile-pic'];

            $sql = "UPDATE admin SET pic_id = ? WHERE Username = ?"; 

            if($stmt = $conn->prepare($sql)){
                $stmt->bind_param("is", $value, $username);

                if ($stmt->execute()) {
                    echo "profile pic Updated successfully";
                } else {
                    echo "Statement Error: " . $stmt->error;
                    echo "profile pic Not updated :(";
                }

            }else {
                echo "Connection Error: " . $conn->error;
            }

            $_SESSION['profile-pic'] = $value;
        }


        if (isset($_POST['new_username']) && trim($_POST['new_username']) !== "") {
            $value = trim($_POST['new_username']);

            // Check for duplicate username
            $checkSql = "SELECT Username FROM admin WHERE Username = ? AND Username != ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("ss", $value, $username);
            $checkStmt->execute();
            $checkStmt->store_result();
            if ($checkStmt->num_rows > 0) {
                $error_messages[] = "Username already taken.";
            } else {
                
                $sql = "UPDATE admin SET Username = ? WHERE Username = ?";

                if($stmt = $conn->prepare($sql)){
                    $stmt->bind_param("ss", $value, $username);

                    if ($stmt->execute()) {
                        echo "username Updated successfully";
                        $username = $value;
                        $_SESSION['username'] = $value;
                    } else {
                        echo "Statement Error: " . $stmt->error;
                        echo "Username Not updated :(";
                    }
                }else {
                    echo "Connection Error: " . $conn->error;
                }
            }
            
        }


        if (isset($_POST['email']) && trim($_POST['email']) !== "") {
            $value = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $error_messages[] = "Invalid email format.";
            }

            // Check for duplicate username
            $checkSql = "SELECT Email FROM admin WHERE Email = ? AND Username != ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("ss", $value, $username);
            $checkStmt->execute();
            $checkStmt->store_result();
            if ($checkStmt->num_rows > 0) {
                $error_messages[] = "An account already exits with that email.";
            } else {
                $sql = "UPDATE admin SET Email = ? WHERE Username = ?";

                if($stmt = $conn->prepare($sql)){
                    $stmt->bind_param("ss", $value, $username);

                    if ($stmt->execute()) {
                        if($stmt->affected_rows == 1){
                            $success_messages[] = "Email Updated successfully!";
                        }else{
                            $error_messages[] = "Failed to Update Email  :(";
                        }
                    } else {
                        echo "Statement Error: " . $stmt->error;
                        echo "email Not updated :(";
                    }

                }else {
                    echo "Connection Error: " . $conn->error;
                }
            }
        }


        if ((isset($_POST['password']) && !empty($_POST['password'])) || (isset($_POST['confirmation']) && !empty($_POST['confirmation']))) {
            $password = trim($_POST['password']);
            $confirmation = trim($_POST['confirmation']);

            if (empty($password) || empty($confirmation)) {
                    $error_messages[] = "Both password fields are required.";
            }elseif(!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)){
                $error_messages[] = "Password must contain at least 8 characters, including letters and numbers.";
            }elseif($password != $confirmation){
                $error_messages[] = "Passwords don't match";
            }else{

                $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $value = $hashed_password;

                $sql = "UPDATE admin SET Password = ? WHERE Username = ?";
        
                if($stmt = $conn->prepare($sql)){
                    $stmt->bind_param("ss", $value, $username);

                    if ($stmt->execute()) {
                        if($stmt->affected_rows == 1){
                            $success_messages[] = "Password Updated successfully!";
                        }else{
                            $error_messages[] = "Failed to Update Password  :(";
                        }                     } else {
                        echo "Statement Error: " . $stmt->error;
                        echo "Password Not updated :(";
                    }

                }else {
                    echo "Connection Error: " . $conn->error;
                }
            }
        }


        //execution
        if (!empty($success_messages)) {
            $_SESSION['success_message'] = implode(" ", $success_messages);
        }
        if (!empty($error_messages)) {
            $_SESSION['error_message'] = implode(" ", $error_messages);
            header("Location: ../editAdminProfile.php");
            exit();
        }

        header("Location: ../adminProfile.php");
        exit();
    }
?> 