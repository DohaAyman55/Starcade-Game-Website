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

        $username = $_POST['username'];
        $password = $_POST['password'];
        $admin = isset($_POST['isAdmin']) ? true : false;

        if($admin){
            $sql = "SELECT * FROM admin WHERE Username = ? OR Email = ?";  
        }else{
            $sql = "SELECT * FROM user WHERE Username = ? OR Email = ?";  
        }

        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("ss", $username, $username);
    
            if ($stmt->execute()) {
                $resultSet = $stmt->get_result();
                if($resultSet->num_rows == 1){
                    $user = $resultSet->fetch_assoc();
                    if(password_verify($password, hash: $user['Password'])){      //compare entered password with hashed one
                       
                        if($admin){
                            $_SESSION['username'] = $user['Username'];
                            $_SESSION['admin_id'] = $user['id'];
                            header("Location: ../admin/Dashboard.php");
                            exit();
                        }else{
                            if($user['status'] == 'active'){
                                $_SESSION['username'] = $user['Username'];
                                header(header: "Location: ../MainPage.php");
                                exit();
                            }else{
                                $email = $user['Email'];
                                echo "Your account was are banned!!";
                                header('Location: ../requestActivation.php');
                                exit();
                            }
                        }
                    }else{
                        echo 'incorrect password';
                    }
    
                }else{
                    echo "no account with that username.";
                }
            } else {
                echo "Statement Error: " . $stmt->error;
            }
    
        } else {
            echo "Connection Error: " . $conn->error;
        }
    
        $conn->close();

    }  
?>