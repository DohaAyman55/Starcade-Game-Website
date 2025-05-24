<?php
    include("../php/connection.php");

    session_start();
    $_SESSION['form_token'] = bin2hex(random_bytes(32));

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
?>



<?php
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $password = $_POST['admin_password'];

        $sql = "SELECT Password FROM admin WHERE id = ?";

        if($stmt = $conn->prepare($sql)){
            $admin_id = 1;
            $stmt->bind_param("i", $admin_id);
    
            if ($stmt->execute()) {
                $resultSet = $stmt->get_result();
                if($resultSet->num_rows == 1){
                    $admin = $resultSet->fetch_assoc();
                    if(!password_verify($password, $admin['Password'])){
                        $_SESSION['error_message'] =  "Access Denied";
                        header("Location: checkAccess.php");
                        exit;
                    }else{
                        $_SESSION['is_admin_authenticated'] = true;
                        unset($_SESSION['form_token']);
                        $_SESSION['form_token'] = bin2hex(random_bytes(32));
                    }
                }else{
                    echo "invalid admin id!";
                }
            } else {
                echo "Error: " . $stmt->error;
            }

        } else {
            echo "Error: " . $conn->error;
        }
    }
?>

<?php if (isset($_SESSION['is_admin_authenticated']) && $_SESSION['is_admin_authenticated']): ?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8"/>
        <link rel="stylesheet" href="../css/admin_settings.css"/>
        <script src="js/mechanism.js" defer></script>
        <title>Admin Info</title>
    </head>
    <body>
        <?php include '../bars/adminNav.php' ?>

        <div id = 'content'>
            <!-- <p>oops this page is currently under construction please be patient we are working very hard!</p> -->
            <h1>ADMIN ACCOUNTS</h1>
                <div id="add_game">
                    <a href="addAdminForm.php"><button id="addbtn">ADD ADMIN</button></a>
                </div>
            <table id="user-accounts">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Restriction</th>
                    </tr>
                </thead>
                <tbody id="body">
                    <?php
                        $sql = "SELECT 
                                    a.Username,
                                    a.Email,
                                    a.status,
                                    p.picture
                                FROM 
                                    admin a
                                JOIN 
                                    profile_pic p ON a.pic_id = p.pic_id
                                WHERE
                                    a.id != ". $_SESSION['admin_id'];
                    

                        if($stmt = $conn->prepare($sql)){
                            if ($stmt->execute()) {
                                $players = $stmt->get_result();
                                if($players->num_rows > 0){
                                    while($player = $players->fetch_assoc()){
                                        $username = $player['Username'];
                                        $email = $player['Email'];
                                        $status = $player['status'];
                                        $pic = $player['picture'];

                                        echo "<tr>";
                                        
                                        echo "<td><img src='../images/profile-pics/$pic.jpg'/></td>";
                                        echo "<td>$username</td>";
                                        echo "<td>$email</td>";
                                        echo "<td>$status</td>";

                                        if($status == "active"){
                                            echo    "<td> <form action='execute/restrictAdmin.php' method='POST' style='display:inline;'>
                                                                <input type='hidden' name='token' value='" . $_SESSION['form_token'] . "'>

                                                                <input type='hidden' name='username' value='$username'>
                                                                <button type='submit' onclick=\"return confirm('Are you sure you want to ban this player: $username?')\" class='deletebtn'>RESTRICT</button>
                                                            </form> </td>";
                                        }else if($status == "banned"){
                                            echo    "<td> <form action='execute/activateAdmin.php' method='POST' style='display:inline;'>
                                                            <input type='hidden' name='token' value='" . $_SESSION['form_token'] . "'>

                                                            <input type='hidden' name='username' value='$username'>
                                                            <button type='submit' onclick=\"return confirm('Are you sure you want to activate this player: $username?')\" class='deletebtn'>ACTIVATE</button>
                                                          </form> </td>";
                                        }

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



<?php else: ?>
    <p style="color:red; text-align:center;">Access Denied. Please log in as admin.</p>
<?php endif; ?>
<?php $conn->close(); ?>