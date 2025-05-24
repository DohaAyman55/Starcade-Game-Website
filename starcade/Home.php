<?php
    session_start();

    if(isset($_SESSION['admin_id'])){
        header('Location: admin/Dashboard.php');
        exit();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8"/>
        <link rel="stylesheet" href="css\homePage.css"/>
        <script src="js/mechanism.js" defer></script>
        <script
            src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs"
            type="module"
            ></script>
        <title>Home</title>
    </head>
    <body>
        <?php include 'bars/userNav.php' ?>

        <div id="content">
            <!--spaceship animation?-->
            <dotlottie-player
                src="https://lottie.host/14869abc-0c6a-4a01-840e-27599fee8df7/wwpwNZG4PI.lottie"
                background="transparent"
                speed="1"
                style="width: 500px; height: 500px"
                loop
                autoplay
            ></dotlottie-player>
            <div id = 'CTA'>
                <p>Enjoy a variety of games that suit all ages and are fun for everyone. Ranging from single player to multiplayer and diverse genres!</p>
            <!--IF ALREADY LOGGED IN GO TO MAIN PAGE-->
                <button>
                    <?php if (isset($_SESSION['username'])): ?>
                        <a href="MainPage.php">PLAY NOW!</a>                 
                    <?php else: ?>
                        <a href="<?php echo isset($_SESSION['username']) ? 'mainpage.php' : 'LoginForm.php';?>">PLAY NOW!</a>
                    <?php endif; ?>
                </button>
            </div> 
        </div>
        <?php include 'bars/userAbout.php'; ?>
    </body>
</html>