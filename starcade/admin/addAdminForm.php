<?php
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
    
    // Update last activity timestamp
    $_SESSION['LAST_ACTIVITY'] = time();
?>

<!DOCTYPE html>
<html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/Space-login.css" rel="stylesheet"/>
    <script src="js/mechanism.js" defer></script>

    <title>Add New Admin</title>
    </head>
    <body>
        <div id="centred">
            <div id="container">
                <div id="logo">
                    <img id="icon" src="../images/icons/spaceship.png"/>
                    <h1>STARCADE</h1>
                </div>
                <h1>Add Admin</h1>
                <form action="execute/adminSignup.php" method="POST">
                    <input type="hidden" name="token" value="<?= $_SESSION['form_token'] ?>">

                    <label>Username</label>
                    <input class="textField" type="text" name="username" placeholder="Enter your Username" required/>
                    <label>Password</label>
                    <input class="textField" type="password" name="password" placeholder="Enter your password" required/>
                    <label>Confirm Password</label>
                    <input class="textField" type="password" name="confirm_password" placeholder="Confirm password" required/>
                    <label>Email</label>
                    <input class="textField" type="email" name="email" placeholder="Enter your email" required/><br/>
                    <input type="submit" name="submit" value="ADD ADMIN"/>
                </form>

                <?php
                    if (isset($_SESSION['error_message'])) {
                        echo "<p class='error'>" . $_SESSION['error_message'] . "</p>";
                        unset($_SESSION['error_message']);
                    }
                ?>
            </div>
        </div>
    </body>
</html>


