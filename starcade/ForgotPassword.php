<?php
    session_start();
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html>
    <head>
        <link href="css/Space-login.css" rel="stylesheet"/>
        <title>Verify Email</title>
    </head>
    <body>
        <div id="centred">
            <div id="container">
                <div id="logo">
                    <img id="icon" src="images/icons/spaceship.png"/>
                    <h1>STARCADE</h1>
                </div>
                <form action="php/SendCode.php" method="POST">
                    <input type="hidden" name="token" value="<?= $_SESSION['form_token'] ?>">
                    
                    <label>Email</label>
                    <input class="textField" type="text" name="email" placeholder="Enter Your Email Address" required/>
                    <input type="submit" name="submit" value="next" />
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