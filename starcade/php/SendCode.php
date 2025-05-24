<?php
    session_start(); //to store email verification data
    //$timeout_duration = 900; //code expires after 15 mins
    include ("connection.php");
    include ('mail-function.php');


    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        
        if ($_POST['token'] !== $_SESSION['form_token']) {
            $_SESSION['error'] = "Invalid or duplicate submission.";
            header( "Location: " . $_SERVER['HTTP_REFERER']);         
            exit();
        }
        unset($_SESSION['form_token']);

        if(isset($_SESSION['signup']) && $_SESSION['signup']){
            sendMail($_SESSION['pending_user']['email']);
            header("Location: ../verifyEmail.php");
            exit();
        }else{
            $mail = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : '';  

            if (empty($mail)) {
                $_SESSION['error_message'] = "Email address is required.";
                    header("Location: ../ForgotPassword.php");         
                exit();
            }
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error_message'] = "Invalid email address.";
                    header("Location: ../ForgotPassword.php");         
                exit();
            }

            $sql = "SELECT Email FROM user WHERE Email = ?
                    UNION
                    SELECT Email FROM admin WHERE Email = ?";

            if($stmt = $conn->prepare($sql)){
                $stmt->bind_param("ss", $mail, $mail);
                if($stmt->execute()){
                    $result = $stmt->get_result();
                    if($result->num_rows > 0){
                        sendMail($mail);
                        header("Location: ../verifyEmail.php");
                        exit();
                    }else{
                        $_SESSION['error_message'] = "No Account with that Email.";
                        header("Location: ../ForgotPassword.php");         
                        exit();
                    } 
                }else{
                    echo "Statement Error: " . $stmt->error;
                }
            }else{
                echo "Connection Error: " . $conn->error;
            }
        }
    }
?>