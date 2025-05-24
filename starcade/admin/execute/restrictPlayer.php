<?php
    session_start();
    include '../../php/connection.php'; 

    if($_SERVER['REQUEST_METHOD'] === 'POST'){

        if ($_POST['token'] !== $_SESSION['form_token']) {
            $_SESSION['error'] = "Invalid or duplicate submission.";
            header("Location: " . $_SERVER['HTTP_REFERER']);         
            exit();
        }
        unset($_SESSION['form_token']);

        if(isset($_POST['username'])){
            $username = $_POST['username'];
            $sql = "UPDATE user SET status = ? WHERE Username = ?";
            $status = "banned";
            
            if($stmt = $conn->prepare($sql)){
                $stmt->bind_param("ss", $status ,$username);
                if ($stmt->execute()) {
                    if ($stmt->affected_rows > 0) {
                        echo "Player account restricted";
                        header('Location: ../viewUsers.php');
                        exit();
                    } else {
                        echo "No user found with that username.";
                    }
                }else{
                    echo "Statement Error: " . $stmt->error;
                }
            }else{
                echo "Connection Error: " . $conn->error;
            }
        }else{
            echo "no username detected.";
        }
    }else{
        echo 'Invalid request method.';
    }
?>