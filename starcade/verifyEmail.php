<?php
    session_start();
    include 'php/connection.php';
    $_SESSION['form_token'] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html>
    
    <head>
        <link href="css/Space-login.css" rel="stylesheet"/>
        <title>Two Factor Authentication</title>
    </head>
    <body>
        <div id="centred">
            <div id="container">
                <div id="logo">
                    <img id="icon" src="images/icons/spaceship.png"/>
                    <h1>STARCADE</h1>
                </div>
                <form action="php/verification.php" method="POST">
                    <input type="hidden" name="token" value="<?= $_SESSION['form_token'] ?>">

                    <label>Verify Email</label>
                    <input class="textField" type="text" name="code" placeholder="Enter Verification Code" required/>
                    <p> A verification code has been sent to your email. </p>
                    <input type="submit" name="submit" value="next"/>
                </form>
                <form action="php/SendCode.php" method="POST">
                    <input type="hidden" name="token" value="<?= $_SESSION['form_token'] ?>">

                    <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>" />
                    <button type="submit" class="resendbtn">RESEND CODE</button>
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