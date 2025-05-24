<?php
    include('../php/connection.php');
    session_start();

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    // Check if the user is logged in
    if (!isset($_SESSION['username'])) {
        header("Location: ../LoginForm.php");
        exit();
    }
    // deny access to admin page if user is not admin
    if(!isset($_SESSION['admin_id'])){
        header("Location: ../Home.php");
        exit();
    }

    // session timeout 15 minutes
    $timeout_duration = 900;
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
        header("Location: ../php/logout.php");
        exit();
    }
    $_SESSION['LAST_ACTIVITY'] = time();





    $sql = "SELECT profile_pic.picture
            FROM admin
            JOIN profile_pic ON admin.pic_id = profile_pic.pic_id
            WHERE admin.id = ?"; 

    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("i", $_SESSION['admin_id']);
        if ($stmt->execute()) {
            $admins = $stmt->get_result();
            if($admins->num_rows > 0){
                $admin = $admins->fetch_assoc();
            }else{
                echo "Admin doesn't exist";
            }
        }else{
            echo "Statement Error: " . $stmt->error;
        }
    }else{
        echo "Connection Error: " . $conn->error;
    }

    $pic = isset($admin['picture']) ? $admin['picture'] : "default";
    $_SESSION['profile-pic'] = "../images/profile-pics/$pic.jpg";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8"/>
        <link rel="stylesheet" href="../css/admin_settings.css"/>
        <script src="../js/mechanism.js" defer></script>
        <script src="../js/edit.js" defer></script>
        <title>Admin Logs</title>
    </head>
    <body>
        <?php include '../bars/adminNav.php' ?>

        <div id="content">
        <h1>ADMIN LOGS</h1>
            <table id="user-accounts">
                <thead>
                    <tr>
                        <th>LOG#</th>
                        <th>ADMIN</th>
                        <th>ACTION</th>
                        <th>IP_ADDRESS</th>
                        <th>CREATED_AT</th>
                    </tr>
                </thead>
                <tbody id="body">
                    <?php
                        $sql = "SELECT 
                                    admin_logs.id, 
                                    admin.Username, 
                                    admin_logs.action, 
                                    admin_logs.ip_address,
                                    admin_logs.created_at 
                                FROM 
                                    admin_logs
                                JOIN 
                                    admin ON admin_logs.admin_id = admin.id
                                ORDER BY 
                                    admin_logs.created_at DESC";

                        if($stmt = $conn->prepare($sql)){
                            if ($stmt->execute()) {
                                $logs = $stmt->get_result();
                                if($logs->num_rows > 0){
                                    while($log = $logs->fetch_assoc()){
                                        $logId = $log['id'];
                                        $admin = $log['Username'];
                                        $ip = $log['ip_address'];
                                        $status = $log['action'];
                                        $pic = $log['created_at'];

                                        echo "<tr>";
                                        
                                        echo "<td>$logId</td>";
                                        echo "<td>$admin</td>";
                                        echo "<td>$ip</td>";
                                        echo "<td>$status</td>";
                                        echo "<td>$pic</td>";

                                        echo "</tr>";
                                    }
                                }else{
                                    echo "No players registered.";
                                }
                            }else{
                                echo "Statement Error: " . $stmt->error;
                            }
                        }else{
                            echo "Connection Error: " . $conn->error;
                        }
                    ?>                
                </tbody>
            </table>
        </div>
        <?php include '../bars/adminAbout.php' ?>
    </body>
</html>