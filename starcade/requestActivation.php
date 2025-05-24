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

    <title>Request Activation</title>
    </head>
    <body>
        <div id="centred">
            <div id="container">
                <div id="logo">
                    <img id="icon" src="images/icons/spaceship.png"/>
                    <h1>STARCADE</h1>
                </div>
                <h1>OOPS! YOUR ACCOUNT HAS BEEN BANNED :(</h1>
                <form action="admin/execute/sendRequest.php" method="POST">
                    <input type="hidden" name="token" value="<?= $_SESSION['form_token'] ?>">
                    
                    <input type="hidden" name="email" value="<?= $_SESSION['banned_email'] ?? '' ?>"/>
                    <input type="submit" name="submit" value="SEND ACTIVATION REQUEST"/>
                </form>
            </div>
        </div>
    </body>
</html>