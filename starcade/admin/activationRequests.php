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

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8"/>
        <link rel="stylesheet" href="../css/admin_settings.css"/>
        <script src="js/mechanism.js" defer></script>
        <title>Activation Requests</title>
    </head>
    <body>
        <?php include '../bars/adminNav.php' ?>

        <div id = 'content'>
            <!-- <p>oops this page is currently under construction please be patient we are working very hard!</p> -->
            <h1>ACTIVATION REQUESTS</h1>
            <table id="user-accounts">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ROLE</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Restriction</th>
                    </tr>
                </thead>
                <tbody id="body">
                    <?php
                        $sql = "(
                                    SELECT 
                                        u.Username,
                                        u.Email,
                                        u.status,
                                        p.picture,
                                        'Player' AS role
                                    FROM 
                                        user u
                                    JOIN 
                                        profile_pic p ON u.pic_id = p.pic_id
                                    WHERE 
                                        u.status = ? AND requestedActivation = ?
                                )
                                UNION
                                (
                                    SELECT 
                                        a.Username,
                                        a.Email,
                                        a.status,
                                        p.picture,
                                        'Admin' AS role
                                    FROM 
                                        admin a
                                    JOIN 
                                        profile_pic p ON a.pic_id = p.pic_id
                                    WHERE 
                                        a.status = ? AND requestedActivation = ?
                                )";


                        if($stmt = $conn->prepare($sql)){
                            $banned = "banned";
                            $request = true;
                            $stmt->bind_param("sisi", $banned, $request, $banned, $request);

                            if ($stmt->execute()) {
                                $players = $stmt->get_result();
                                if($players->num_rows > 0){
                                    while($player = $players->fetch_assoc()){
                                        $role = $player['role'];
                                        $username = $player['Username'];
                                        $email = $player['Email'];
                                        $status = $player['status'];
                                        $pic = $player['picture'];

                                        echo "<tr>";
                                        
                                        echo "<td><img src='../images/profile-pics/$pic.jpg'/></td>";
                                        echo "<td>$role</td>";
                                        echo "<td>$username</td>";
                                        echo "<td>$email</td>";
                                        echo "<td>$status</td>";

                                        echo    "<td> <form action='execute/activate".$role.".php' method='POST' style='display:inline;'>
                                                            <input type='hidden' name='token' value='" . $_SESSION['form_token'] . "'>

                                                            <input type='hidden' name='username' value='$username'>
                                                            <button type='submit' onclick=\"return confirm('Are you sure you want to activate this $role: $username?')\" class='deletebtn'>ACTIVATE</button>
                                                        </form> </td>";
                                        

                                        echo "</tr>";
                                    }
                                }else{
                                    echo "No Activation Requests.";
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