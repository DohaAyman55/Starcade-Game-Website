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
?>

<!DOCTYPE html> 
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8"/>
        <link rel="stylesheet" href="css/leaderboard.css"/>
        <!-- <script src="js/leaderboard.js" defer></script>  -->
        <script src="js/mechanism.js" defer></script>

        <title>Leaderboard</title>
    </head>
    <body>
        <?php include 'bars/userNav.php' ?>

        <h1>LEADERBOARD</h1>
        <div id="container">
            <table id="leaderboard">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Highscore</th>
                        <th>Game</th>
                    </tr>
                </thead>
                <tbody id="body">
                    <!--HANDLE IN PHP ???????????????-->   
                    <?php
                        $sql = "SELECT scores.*, games.Name
                                FROM scores
                                JOIN games ON scores.Game_id = games.game_id
                                WHERE scores.highscore > 0
                                ORDER BY scores.highscore DESC
                                LIMIT 5;
                                ";

                        if($stmt = $conn->prepare($sql)){
                            if ($stmt->execute()) {
                                $scores = $stmt->get_result();
                                if($scores->num_rows > 0){

                                    while($row = $scores->fetch_assoc()){
                                        $username = $row['Username'];
                                        $highscore = $row['Highscore'];
                                        $game = $row['Name'];

                                        echo "<tr>";
                                        echo "<td>" . $username . "</td>";
                                        echo "<td>" . $highscore . "</td>";
                                        echo "<td>" . $game . "</td>";
                                        echo "</tr>";
                                    }
                                }else{
                                    echo "No scores to display :(";
                                }
                            }else{
                                echo "Statement Error: " . $stmt->error;
                            }
                        }else{
                            echo "Connection Error: " . $conn->error;
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <?php include 'bars/userAbout.php'; ?>
        <footer>
            <p>Powered by just me now =D</p>
        </footer>
    </body>
</html>