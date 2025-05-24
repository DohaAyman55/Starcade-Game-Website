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
    $_SESSION['LAST_ACTIVITY'] = time();
?>


<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8"/>
        <link rel="stylesheet" href="css\mainPage.css"/>
        <script src="mechanism.js" defer></script>
        <title>Choose Game</title>
    </head>
    <body>
        <?php include 'bars/userNav.php' ?>

        <div id="content">
            <!-- DYNAMIC HIGHSCORES -->
            <?php
                $sql = "SELECT * FROM games";

                if($stmt = $conn->prepare($sql)){
                    if ($stmt->execute()) {
                        $games = $stmt->get_result();
                        if($games->num_rows > 0){

                            while($row = $games->fetch_assoc()){
                                $game_id = $row['game_id'];
                                $game_Name = $row['Name'];
                                $game_Logo = $row['Logo'];


                                echo "<a href='gamePage.php?game=$game_id' class='card' 
                                style=\"background-image: url('$game_Logo');\">";
                                echo "<p>" . htmlspecialchars($game_Name) . "</p></a>";
                            }
                        }else{
                            echo "Play games to get a score";
                        }
                    }else{
                        echo "Statement Error: " . $stmt->error;
                    }
                }else{
                    echo "Connection Error: " . $conn->error;
                }
            ?>
        </div>
        <?php include 'bars/userAbout.php'; ?>
    </body>
</html>