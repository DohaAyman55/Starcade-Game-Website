<?php
    include("php/connection.php");

    session_start();

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





    
    //get game id to load iframe src
    $_SESSION['game_id'] = isset($_GET['game']) ? $_GET['game'] : '404Page';

    $game = $_SESSION['game_id'];

    $sql = "SELECT game_id,Name FROM games WHERE game_id = ?";

    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("s", $game);

        if ($stmt->execute()) {
            $available_games = $stmt->get_result();
            if($available_games->num_rows == 1){
                $row = $available_games->fetch_assoc();
                $title = $row['Name'];
                $src = $row['game_id'];
            }else{
                echo "no game with that id.";
            }
        }else {
            echo "Statement Error: " . $stmt->error;
            header("Location: 404Page/404Page.html");
            exit();
        }
    } else {
        echo "Connection Error: " . $conn->error;
        header("Location: 404Page/404Page.html");
        exit();
    }

    $conn->close(); 
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8"/>
        <link rel="stylesheet" href="css/gamePage.css"/>
        <script src="js/mechanism.js" defer></script>
        <script src="js/updateScore.js" defer></script>
        <title><?= $title ?></title>
    </head>
    <body>
        <?php include 'bars/userNav.php' ?> 

        <div id="container">
            <div id = "gamePlane">
                    <iframe id="game" src="<?= $src ?>"> </iframe>                                                                                   
            </div>
            <div id = "side">
                <div id="AdventureTime" class="card"><p>Adventure Time</p></div>
                <div id="ClickerCat" class="card"><p>Clicker Cat</p></div>
                <div id="FroggyQuest" class="card"><p>Froggy Quest</p></div>
                <div id="FlappyBee" class="card"><p>Flappy Bee</p></div>
            </div> 
        </div>
        <?php include 'bars/userAbout.php'; ?>
    </body>
</html>