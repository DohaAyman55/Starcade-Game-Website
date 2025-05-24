<?php
    session_start();
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
?>
 
<!DOCTYPE html>
<html>
    <head>
        <link href="css/Space-login.css" rel="stylesheet"/>
        <title>Change Password</title>
    </head>
    <body>
        <div id="centred">
            <div id="container">
                <div id="logo">
                    <img id="icon" src="images/icons/spaceship.png"/>
                    <h1>STARCADE</h1>
                </div>
                <form action="php/changePass.php" method="POST">
                    <input type="hidden" name="token" value="<?= $_SESSION['form_token'] ?>">

                    <label>Create Password</label>
                    <input class="textField" type="password" name="new-pass" placeholder="Enter new password"/>
                    <label>Confirm Password</label>
                    <input class="textField" type="password" name="confirm-pass" placeholder="Confirm Password"/>

                    <input type="submit" name="submit" value="Confirm"/>
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
