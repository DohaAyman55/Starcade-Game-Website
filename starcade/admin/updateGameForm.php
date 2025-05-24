<?php
    include("../php/connection.php");

    session_start();
    $_SESSION['form_token'] = bin2hex(random_bytes(32));

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





    //get game id from previous page and get its values from db
    $_SESSION["game_id"] = isset($_GET['game']) ? $_GET['game'] : '404Page';
    $gameId = $_SESSION["game_id"];

    $sql = "SELECT * FROM games WHERE game_id = ?";

    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("s", $gameId);

        if ($stmt->execute()) {
            $games = $stmt->get_result();
            if($games->num_rows == 1){
                $row = $games->fetch_assoc();
                $gameTitle = $row['Name'];
            }else{
                echo "no game with that id.";
            }
        }else {
            echo "Statement Error: " . $stmt->error;
            header("Location: ../404Page/404Page.html");
            exit();
        }
    } else {
        echo "Connection Error: " . $conn->error;
        header("Location: ../404Page/404Page.html");
        exit();
    }

    $conn->close(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Game</title>
    <link rel="stylesheet" href="../css/Space-login.css">
<body>
    <div id="centred">
        <div id="container">
            <div id="logo">
                <img id="icon" src="../images/icons/spaceship.png" />
                <h1>STARCADE</h1>
            </div>
            <h1>Update Game Details</h1>
            <form action="execute/updateGame.php" method="POST">
                <input type="hidden" name="token" value="<?= $_SESSION['form_token'] ?>">

                <label for="name">Title:</label>
                <input class="textField" type="text" id="name" name="name" placeholder="<?= $gameTitle?>" required/>

                <input type="hidden" name="game_path"  value="<?= $gameTitle.'/'.$gameTitle.'.html'?>"></input>

                <input type="hidden" name="logo" value="<?= $gameTitle.'/'.$gameTitle.'.jpg'?>"/>

                <input type="submit" value="Update Game Info"/>
            </form>
            <a href="viewGames.php" id="cancelbtn"><button>CANCEL</button></a>
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