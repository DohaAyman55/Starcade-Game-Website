<?php
    include("php/connection.php");

    session_start();
    $_SESSION['form_token'] = bin2hex(random_bytes(32));

    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    if (!isset($_SESSION['username'])) {
        header("Location: LoginForm.php");
        exit();
    }
    if(isset($_SESSION['admin_id'])){
        header("Location: admin/Dashboard.php");
        exit();
    }

    // session timeout 15 minutes
    $timeout_duration = 900;
    if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
        header("Location: php/logout.php");
        exit();
    }
    
    // Update last activity timestamp
    $_SESSION['LAST_ACTIVITY'] = time();








    //get user's email from db
    $sql = "SELECT Email FROM user WHERE Username = ?";

    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("s", $_SESSION['username']);
        if ($stmt->execute()) {
            $users = $stmt->get_result();
            if($users->num_rows == 1){
                $user = $users->fetch_assoc();
                $_SESSION['email'] = $user['Email'];
            }else{
                echo "Username doesn't exist";
            }
        }else{
            echo "Statement Error: " . $stmt->error;
        }
    }else{
        echo "Connection Error: " . $conn->error;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8"/>
        <link rel="stylesheet" href="css/editProfile.css"/>
        <script src="js/mechanism.js" defer></script>
        <title>Edit Profile</title>
    </head>
    <body>
        <?php include 'bars/userNav.php' ?>

        <div id="content">
            <div id="user-info">
                <img src="<?= $_SESSION['profile-pic'] ?>" id="profile-pic"/>
                <div id="username">
                    <h3><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
                </div>
                <form action="php/deleteUser.php" method="POST">
                    <input type='hidden' name='username' value='<?= $_SESSION['username'] ?>'>
                    <button onclick="return confirm('Are you sure you want to delete your account?\nAll scores will be lost!')" class="red">DELETE ACCOUNT</button>
                </form>
            </div>
            <div id="profile-details">
                <div id="cancelbtn">
                    <h1>ACCOUNT</h1>
                    <a href="profile.php"><button class="red">X</button></a>
                </div>

                <form action="php/edit.php" method="POST">
                    <div id="pic-container">
                        <!-- DYNAMICALLY ADD PROFILE PICS -->
                        <?php
                            $sql = "SELECT pic_id, picture FROM profile_pic WHERE type = ? ORDER BY pic_id ASC";

                            if($stmt = $conn->prepare($sql)){
                                $type = "user";
                                $stmt->bind_param("s", $type);
                                if ($stmt->execute()) {
                                    $pictures = $stmt->get_result();
                                    if($pictures->num_rows > 0){
                                        while($row = $pictures->fetch_assoc()){
                                            $picture = htmlspecialchars($row['picture']);
                                            $pic_id = $row['pic_id'];
        
                                            echo "<input type='radio' value='$pic_id' id='$pic_id' name='profile-pic' hidden/>
                                            <label for='$pic_id' class='pic'>
                                            <img src='images/profile-pics/$picture.jpg' alt='Profile Pic'>
                                            </label>";
                                        }
                                    }else{
                                        echo "Filed to load profile pictures";
                                    }
                                }else{
                                    echo "Statement Error: " . $stmt->error;
                                }
                            }else{
                                echo "Connection Error: " . $conn->error;
                            }
                        ?>
                    </div>

                    <input type="hidden" name="token" value="<?= $_SESSION['form_token'] ?>">
                    
                    <label>Username</label>
                    <input type="text" name="new_username" placeholder="<?php echo htmlspecialchars($_SESSION['username']); ?>"/>

                    <label>Email</label>
                    <input type="email" name="email" placeholder="<?= $_SESSION['email'] ?>"/>

                    <label>New Password</label>
                    <input type="password" name="password" placeholder="Leave blank to keep current" />

                    <label>Confirm Password</label>
                    <input type="password" name="confirmation" placeholder="Leave blank to keep current" />

                    <div id="form-controls">
                        <?php
                            if (isset($_SESSION['error_message'])) { 
                                echo "<p class='error'>" . $_SESSION['error_message'] . "</p>";
                                unset($_SESSION['error_message']); 
                            }
                        ?>
                        <button type="reset" name="reset">Clear</button>
                        <button type="submit" name="update">Update Profile</button>
                    </div>                    
                </form>
            </div>
        </div>
        <?php include 'bars/userAbout.php'; ?>
    </body>
</html>