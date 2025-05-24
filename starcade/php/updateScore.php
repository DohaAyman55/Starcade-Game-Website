<?php
    include('connection.php'); 
    session_start();
    $game = $_SESSION['game_id'];
    
    //current score sent from js file
    $data = json_decode(file_get_contents('php://input'), true);
    $currentScore = (int)$data['score'];


    //retreive highscore from database
    $username = $_SESSION['username'];
    $sql = "SELECT Highscore FROM scores WHERE Username = ? AND Game_id = ?";
    
    if($stmt = $conn->prepare($sql)){
        $stmt->bind_param("ss", $username, $game);

        if ($stmt->execute()) {
            $available_scores = $stmt->get_result();
            if($available_scores->num_rows == 1){
                $row = $available_scores->fetch_assoc();

                //compare retreived highscore with current score
                if ($currentScore > $row['Highscore']) {
                    $update_sql = "UPDATE scores SET Highscore = ? WHERE Username = ? AND Game_id = ?";
                    if($update_stmt = $conn->prepare($update_sql)){
                        $update_stmt->bind_param("iss", $currentScore, $username, $game);
                        if ($update_stmt->execute()) {
                            echo "Highscore updated successfully!";
                        } else {
                            echo "Error updating highscore: " . $update_stmt->error;
                        }
                    }else{
                        echo "Statement Error: " . $stmt->error;
                    }
                }
            }else{
                echo "no game with that id.";
            }
        }else {
            echo "Statement Error: " . $stmt->error;
        }
    } else {
        echo "Connection Error: " . $conn->error;
    }

    $conn->close();
?>