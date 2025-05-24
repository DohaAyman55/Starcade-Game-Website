<?php
session_start();
include '../../php/connection.php';
include 'adminLogs.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ($_POST['token'] !== $_SESSION['form_token']) {
        $_SESSION['error'] = "Invalid or duplicate submission.";
        header("Location: " . $_SERVER['HTTP_REFERER']);         
        exit();
    }
    unset($_SESSION['form_token']);

    $gameId = $_POST['game_id'];

    $delete_game = "DELETE FROM games WHERE game_id = ?";

    if($stmt = $conn->prepare($delete_game)){
        $stmt->bind_param("s", $gameId);
        if($stmt->execute()){
            echo "Game deleted successfully!";
        }else{
            echo "Statement Error: ". $stmt->error;
        }
    }else{
        echo "Connection Error: ". $conn->error;
    }

    $delete_scores = "DELETE FROM scores WHERE game_id = ?";

    if($stmt = $conn->prepare($delete_scores)){
        $stmt->bind_param("s", $gameId);
        if($stmt->execute()){
            echo "Game deleted successfully!";
        }else{
            echo "Statement Error: ". $stmt->error;
        }
    }else{
        echo "Connection Error: ". $conn->error;
    }


    logAdminActivity($_SESSION['admin_id'], "Deleted a game with path: $gameId");
    header('Location: ../viewGames.php');
    exit();
}