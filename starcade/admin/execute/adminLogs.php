<?php
function logAdminActivity($adminId, $action) {
    if (session_status() === PHP_SESSION_NONE) { //avoid duplicate session_start()
        session_start();
    }
    include 'C:\xampp\htdocs\Project\GameSite\php\connection.php';

    //get ip address of current admin session
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'UNKNOWN';

    $sql = "INSERT INTO admin_logs (admin_id, action, ip_address) VALUES (?, ?, ?)";

    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("iss", $adminId, $action, $ip);
        if($stmt->execute()){
            echo 'Action logged successfully!';
        }else{
            echo "Statement Error: " . $stmt->error;
        }
    }else{
        echo "Connection Error: " . (is_object($conn) ? $conn->error : "Invalid database connection."); //ensure connection object is initiallised
    }
};