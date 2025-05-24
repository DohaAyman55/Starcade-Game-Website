<?php
    include ('connection.php');
    session_start(); 

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if ($_POST['token'] !== $_SESSION['form_token']) {
            $_SESSION['error'] = "Invalid or duplicate submission.";
            header("Location: " . $_SERVER['HTTP_REFERER']);         
            exit();
        }
        
        $username = isset($_POST['username']) ? trim($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $remember = isset($_POST['remember']);
        $time = time() + (60 * 60 * 24 * 30);


        if (empty($username) || empty($password)) {
            $_SESSION['error_message'] = "Username and password are required.";
            header("Location: ../LoginForm.php");
            exit();
        }
        
        $sql = "SELECT * FROM admin WHERE Username = ? OR Email = ?";        //check admin table
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("ss", $username, $username);
    
            if ($stmt->execute()) {
                $resultSet = $stmt->get_result();
                if($resultSet->num_rows == 1){
                    $user = $resultSet->fetch_assoc();
                    if(password_verify($password, $user['Password'])){      //compare entered password with hashed one
                        if ($user['status'] === 'banned') { 
                            $_SESSION['banned_email'] = $user['Email'];                     //check if admin is banned
                            header("Location: ../requestActivation.php");
                            exit();
                        }else{
                            $_SESSION['username'] = $user['Username'];
                            $_SESSION['role'] = 'admin';
                            $_SESSION['admin_id'] = $user['id'];
                            unset($_SESSION['form_token']);
                            if($remember){
                                setcookie('username', $user['Username'], $time, "/", "",0);
                            }
                            header("Location: ../admin/Dashboard.php");
                            exit();
                        }
                    } else{
                        $_SESSION['error_message'] = "Incorrect Password!";
                        header("Location: ../LoginForm.php");
                        exit();
                    } 
                }
            } else {
                    echo "Statement Error: " . $stmt->error;
            }
        } else {
            echo "Connection Error: " . $conn->error;
        }




        $sql = "SELECT * FROM user WHERE Username = ? OR Email = ?";     //check user tbale
        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("ss", $username, $username);

            if ($stmt->execute()) {
                $resultSet = $stmt->get_result();
                if($resultSet->num_rows == 1){
                    $user = $resultSet->fetch_assoc();
                    if(password_verify($password, $user['Password'])){
                        if ($user['status'] === 'banned') {          //check if user is banned
                            $_SESSION['banned_email'] = $user['Email'];
                            header("Location: ../requestActivation.php");
                            exit();
                        }else{
                            $_SESSION['username'] = $user['Username'];
                            $_SESSION['role'] = 'user';
                            unset($_SESSION['form_token']);
                            if($remember){
                                setcookie('username', $user['Username'], $time, "/", "",0);
                            }
                            header("Location: ../MainPage.php");
                            exit();
                        }
                    } else{
                        $_SESSION['error_message'] = "Incorrect Password!";
                        header("Location: ../LoginForm.php");
                        exit();
                    } 
            }else{
                $_SESSION['error_message'] = "no account with that username.";
                header("Location: ../LoginForm.php");
                exit();
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