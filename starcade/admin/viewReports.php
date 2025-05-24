<?php
    include("../php/connection.php");

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
        <title>Reports</title>
    </head>
    <body>
        <?php include '../bars/adminNav.php' ?>

        <div id = 'content'>
            <h1>TOP GAMES</h1>
            <table>
                <thead>
                <tr>
                    <th>TITLE</th>
                    <th>LOGO</th>
                    <th>NUMBER OF PLAYERS</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    $sql = "SELECT 
                                g.Name AS Title,
                                g.Logo,
                                COUNT(DISTINCT s.Username) AS PlayersCount
                            FROM 
                                gamesite.scores s
                            JOIN 
                                gamesite.games g ON s.Game_id = g.game_id
                            WHERE 
                                s.Highscore > 0
                            GROUP BY 
                                g.Name
                            ORDER BY 
                                PlayersCount DESC";

                    if($stmt = $conn->prepare($sql)){
                        if ($stmt->execute()) {
                            $games = $stmt->get_result();
                            if($games->num_rows > 0){

                                while($row = $games->fetch_assoc()){
                                    $game_Logo = $row['Logo'];
                                    $game_Name = $row['Title'];
                                    $player_count = $row['PlayersCount'];

                                    echo "<tr>";

                                    echo "<td><img src='../$game_Logo'/></td>";
                                    echo "<td>$game_Name</td>";
                                    echo "<td>$player_count</td>";

                                    echo "</tr>";
                                }
                            }else{
                                echo "No Scores Acheived";
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