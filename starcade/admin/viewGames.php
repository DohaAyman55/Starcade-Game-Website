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
        header("Location: ../php/logout.php");
        exit();
    }
    $_SESSION['LAST_ACTIVITY'] = time();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8"/>
        <link rel="stylesheet" href="../css/admin_settings.css"/>
        <script src="../js/mechanism.js" defer></script>
        <title>Games Info</title>
    </head>
    <body>
        <?php include '../bars/adminNav.php' ?>

        <div id = 'content'>
            <h1>GAMES</h1>
            <div id="add_game">
                <a href="addGameForm.php"><button id="addbtn">ADD GAME</button></a>
            </div>
            <table>
                <thead>
                <tr>
                    <th>TITLE</th>
                    <th>LOGO</th>
                    <th>OPTIONS</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $sql = "SELECT * FROM games";

                    if($stmt = $conn->prepare($sql)){
                        if ($stmt->execute()) {
                            $games = $stmt->get_result();
                            if($games->num_rows > 0){

                                while($row = $games->fetch_assoc()){
                                    $game_Name = $row['Name'];
                                    $game_id = $row['game_id'];
                                    $game_Logo = $row['Logo'];

                                    echo "<tr>";

                                    echo "<td>$game_Name</td>";
                                    // echo "<td>$game_id</td>";
                                    echo "<td><img src='../$game_Logo'/></td>";
                                    echo "<td>
                                            <div id='options'>
                                                    <form action='execute/deleteGame.php' method='POST' style='display:inline;'>
                                                        <input type='hidden' name='token' value='" . $_SESSION['form_token'] . "'>

                                                        <input type='hidden' name='game_id' value='$game_id'>
                                                        <button type='submit' onclick=\"return confirm('Are you sure you want to delete $game_Name?')\" class='deletebtn'>DELETE</button>
                                                    </form>
                                                    <a href='updateGameForm.php?game=$game_id'>
                                                    <button class='edit-button'>
                                                        <svg class='edit-svgIcon' viewBox='0 0 512 512'>
                                                            <path d='M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z'></path>
                                                        </svg>
                                                        </button>
                                                    </a> 
                                            </div>
                                            </td>";

                                    echo "</tr>";
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
            </table>
        </div> 
        <?php include '../bars/adminAbout.php' ?>
    </body>
</html>