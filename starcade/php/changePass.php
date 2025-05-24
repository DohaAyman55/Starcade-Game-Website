<?php

include ('connection.php');
session_start();    


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($_POST['token'] !== $_SESSION['form_token']) {
        $_SESSION['error'] = "Invalid or duplicate submission.";
        header("Location: " . $_SERVER['HTTP_REFERER']);         
        exit();
    }
    unset($_SESSION['form_token']);
    
    // clean data before use
    $email = isset($_SESSION['email']) ? filter_var(trim($_SESSION['email']), FILTER_SANITIZE_EMAIL) : ''; 
    $password = isset($_POST['new-pass']) ? trim($_POST['new-pass']) : '';
    $confirmation = isset($_POST['confirm-pass']) ? trim($_POST['confirm-pass']) : '';



    //satitizing and vlidating
    if (!empty($email)) {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $_SESSION['error_message'] = "Invalid Email Address.";
            header("Location: ../changePassword.php");
            exit();
        }
    }else{
        $_SESSION['error_message'] = "Email is required.";
        header("Location: ../changePassword.php");
        exit();
    }


    if (!empty($password)) {
        if(!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)){
            $_SESSION['error_message'] = "Password must contain at least 8 characters, including letters and numbers.";
            header("Location: ../changePassword.php");
            exit();
        }
    }else{
        $_SESSION['error_message'] = "Password is required.";
        header("Location: ../changePassword.php");
        exit();
    }


    if (empty($confirmation)) {
        $_SESSION['error_message'] = "Confirmation password is required.";
        header("Location: ../changePassword.php");
        exit();
    }     
    
    if ($password !== $confirmation) {
        $_SESSION['error_message'] = "Passwords don't match.";
        header("Location: ../changePassword.php");
        exit();
    }







    $user_sql = "UPDATE user SET Password = ? WHERE Email = ?";
    $admin_sql =  "UPDATE admin SET Password = ? WHERE Email = ?";    

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $updated = false;

    if($user_stmt = $conn->prepare($user_sql)){
        $user_stmt->bind_param("ss", $hashed_password, $email);
        if($user_stmt->execute()){
            if ($user_stmt->affected_rows > 0) {
                $updated = true;
            }
            $user_stmt->close();
        }else{
            echo "Statement Error: " . $user_stmt->error;
        }
    } else {
        echo "Connection Error: " . $conn->error;
    }


    if($admin_stmt = $conn->prepare($admin_sql)){
        $admin_stmt->bind_param("ss", $hashed_password, $email);
        if($admin_stmt->execute()){
            if ($admin_stmt->affected_rows > 0) {
                $updated = true;
            }
            $admin_stmt->close();
        }else{
            echo "Statement Error: " . $admin_stmt->error;
        }
    } else {
        echo "Connection Error: " . $conn->error;
    }

    $conn->close();

    if($updated){
        $_SESSION['success_message'] = "Password Updated Successfully!.";
        header("Location: ../LoginForm.php");
        exit();
    }else{
        $_SESSION['error_message'] = "No account found with that email.";
        header("Location: ../changePassword.php");
        exit();
    }
}
?>