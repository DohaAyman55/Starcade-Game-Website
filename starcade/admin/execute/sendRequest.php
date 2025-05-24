<?php
    session_start();
    include '../../php/connection.php';

    $banned = $_SESSION['banned_email'];

    $find_type =   "SELECT 'user' AS role, Username, Email 
                    FROM user 
                    WHERE Email = ?

                    UNION

                    SELECT 'admin' AS role, Username, Email 
                    FROM admin 
                    WHERE Email = ?";

    if($stmt = $conn->prepare($find_type)){
        $stmt->bind_param("ss", $banned, $banned);

        if ($stmt->execute()) {
            $resultSet = $stmt->get_result();

            if($resultSet->num_rows == 1){
                $row = $resultSet->fetch_assoc();
                $role = $row['role'];
                $sql = "UPDATE {$role} SET requestedActivation = true WHERE Email = ?";

                if ($updateStmt = $conn->prepare($sql)) {
                    $updateStmt->bind_param("s", $banned);
    
                    if ($updateStmt->execute()) {
                        if ($updateStmt->affected_rows > 0) {
                            $_SESSION['error_message'] = "Activation request sent!";
                        } else {
                            $_SESSION['error_message'] = "Activation already requested.";
                        }
                    } else {
                        echo "Update Statement Error: " . $updateStmt->error;
                    }
    
                    $updateStmt->close();
                } else {
                    echo "Update Connection Error: " . $conn->error;
                }
            } else {
                echo "No account with that email.";
            }
        } else {
            echo "Statement Error: " . $stmt->error;
        }
    }else{
        echo "Connection Error: " . $conn->error;
    }
    $conn->close();

    header('Location: ../../LoginForm.php');
    exit();
?>