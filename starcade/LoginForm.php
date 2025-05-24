<?php
    session_start();
    $_SESSION['form_token'] = bin2hex(random_bytes(32));

    // Disable caching
    header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
    header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");

    include('php/connection.php');

    //timeout after to 15 minutes
    $timeout_duration = 900;

    // Check if user is logged in
    if (isset($_SESSION['username'])) {
        // Check for timeout
        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > $timeout_duration) {
            header("Location: /php/logout.php");
            exit();
        }

        // Update last activity timestamp
        $_SESSION['LAST_ACTIVITY'] = time();




        //if logged in dont go to login, redirect to home page
        header("Location: Home.php");
        exit();
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link href="css/Space-login.css" rel="stylesheet"/>
        <title>Login</title>
    </head>
    <body>
    <div id="centred">
        <div id="container">
            <div id="logo">
                <img id="icon" src="images/icons/spaceship.png"/>
                <h1>STARCADE</h1>
            </div>
            <h1>Login</h1>
            <form action="php/Login.php" method="POST" autocomplete="off">
                <input type="hidden" name="token" value="<?= $_SESSION['form_token'] ?>">

                <label>Username</label>
                <input class="textField" type="text" name="username" placeholder="Enter your Username or email" autocomplete="off" value="<?php echo isset($_COOKIE['Username']) ? htmlspecialchars($_COOKIE['Username']) : ''; ?>" />
                <label>Password</label>
                <input class="textField" type="password" name="password" placeholder="Enter your password" autocomplete="new-password"/>
                <div id="options">
                    <div id="checkbox">
                        <label class="rememberLabel">
                            <input type="checkbox" name="remember" <?php if (isset($_COOKIE['email'])) echo 'checked'; ?>/>Remember me 
                        </label>

                        <!-- <div id="adminToggle">
                            <input type="checkbox" id="checkboxInput" name="isAdmin" value="1">
                            <label for="checkboxInput" class="toggleSwitch"></label>
                            <span id="adminLabel">Admin</span> 
                        </div> -->
                    </div>
                </div>
                <a class='forgot' href="ForgotPassword.php">Forgot Password?</a>
                <input type="submit" name="submit" value="Sign in"/>
            </form>
            <div id="register">
                <h5>Don't have an account?</h5>
                <a href="RegisterationForm.php">Sign Up</a>
            </div>
            <?php
                if (isset($_SESSION['error_message'])) {
                    echo "<p class='error'>" . $_SESSION['error_message'] . "</p>";
                    unset($_SESSION['error_message']);
                }
            ?>
            <?php
                if (isset($_SESSION['success_message'])) {
                    echo "<p class='success'>" . $_SESSION['success_message'] . "</p>";
                    unset($_SESSION['success_message']);
                }
            ?>
        </div>
    </div>
    </body>
</html> 




