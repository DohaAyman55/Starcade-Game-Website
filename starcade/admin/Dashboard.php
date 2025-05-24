<?php
    session_start();

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
        session_unset();
        session_destroy();
        header("Location: ../LoginForm.php");
        exit();
    }
    $_SESSION['LAST_ACTIVITY'] = time();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8"/>
        <link rel="stylesheet" href="../css/dashboard.css"/>
        <script src="../js/mechanism.js" defer></script>
        <title>Admin Dashboard</title>
    </head>
    <body>
        <?php include '../bars/adminNav.php' ?>

        <div id = 'content'>
            <div id="content-wrapper">
            <h2>Hello :D <br/> what are we planning on doing today!</h2>
                <div id="cardsContainer">
                    <a href="viewGames.php" class="card"><p>GAMES</p></a>
                    <a href="viewUsers.php" class="card"><p>USERS</p></a>
                    <a href="activationRequests.php" class="card"><p>ACTIVATION REQUESTS</p></a>
                    <a href="viewLogs.php" class="card"><p>LOGS</p></a>
                    <a href="checkAccess.php" class="card"><p>ADMINS</p></a>
                    <a href="viewReports.php" class="card"><p>STATISTICS</p></a>
                </div>
            </div>
        </div>
        <?php include '../bars/adminAbout.php' ?>
    </body>
</html>