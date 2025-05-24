<?php
session_start();
include '../../php/connection.php';
include '../execute/adminLogs.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

     if ($_POST['token'] !== $_SESSION['form_token']) {
        $_SESSION['error'] = "Invalid or duplicate submission.";
            header("Location: " . $_SERVER['HTTP_REFERER']);         
            exit();
    }
    unset($_SESSION['form_token']);
    
    $gameTitle = $_POST['name'];
    $gameId = $gameTitle.'/'.$gameTitle.'.html';
    $gameLogo = $gameTitle.'/'.$gameTitle.'.jpg';


    // Check if the game already exists
    $check = "SELECT 1 FROM games WHERE Name = ? OR game_id = ?";
    if ($stmt = $conn->prepare($check)) {
        $stmt->bind_param("ss", $gameTitle, $gameId);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $_SESSION['error_message'] = "Game already exists.";
            header("Location: ../addGame.php");
            exit();
        }
        $stmt->close();
    } else {
        echo "Connection Error: " . $conn->error;
        exit();
    }

    
    // add the game to the database
    $insert = "INSERT INTO games (game_id, Name, Logo) VALUES(?,?,?)";

    if($stmt = $conn->prepare($insert)){
        $stmt->bind_param("sss", $gameId, $gameTitle, $gameLogo);
        if($stmt->execute()){
            echo "Game added successfully!";
        }else{
            echo "Statement Error: ". $stmt->error;
        }
    }else{
        echo "Connection Error: ". $conn->error;
    }

    // add the game to the scores table for all users
    $update =  "INSERT INTO scores (Username, Game_id, Highscore)
                SELECT u.Username, ?, 0
                FROM user u
                WHERE NOT EXISTS (
                    SELECT 1 FROM scores s WHERE s.Username = u.Username AND s.Game_id = ?
                )";

    if ($stmt = $conn->prepare($update)) {
        $stmt->bind_param("ss", $gameId, $gameId);
        if ($stmt->execute()) {
            echo "Score added successfully!";
        } else {
            echo "Statement Error: " . $stmt->error;
        }
    } else {
        echo "Connection Error: " . $conn->error;
    }


    logAdminActivity($_SESSION['admin_id'], "Added a game with path: $gameId");
    header('Location: ../viewGames.php');
    exit();
}