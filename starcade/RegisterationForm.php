<?php
    session_start();
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/Space-login.css" rel="stylesheet"/>
    <script src="js/mechanism.js" defer></script>

    <title>Create Account</title>
    </head>
    <body>
        <div id="centred">
            <div id="container">
                <div id="logo">
                    <img id="icon" src="images/icons/spaceship.png" />
                    <h1>STARCADE</h1>
                </div>
                <h1>Create Account</h1>
                <form action="php/Signup.php" method="POST">
                    <input type="hidden" name="token" value="<?= $_SESSION['form_token'] ?>">

                    <label>Username</label>
                    <input class="textField" type="text" name="username" placeholder="Enter your Username" required/>
                    <label>Password</label>
                    <input class="textField" type="password" name="password" placeholder="Enter your password" required/>
                    <label>Confirm Password</label>
                    <input class="textField" type="password" name="confirm_password" placeholder="Confirm password" required/>
                    <label>Email</label>
                    <input class="textField" type="email" name="email" placeholder="Enter your email" required/><br/>
                    <input type="submit" name="submit" value="Sign Up"/>
                </form>
                <div id="register">
                    <h5>Already have an account?</h5>
                    <a href="LoginForm.php">LOGIN</a>
                </div>

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