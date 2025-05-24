<?php
session_start();
include '../../php/connection.php';
include 'adminLogs.php';
$game_id =  $_SESSION["game_id"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ($_POST['token'] !== $_SESSION['form_token']) {
        $_SESSION['error'] = "Invalid or duplicate submission.";
        header("Location: " . $_SERVER['HTTP_REFERER']);         
        exit();
    }
    unset($_SESSION['form_token']);

    if(!empty($_POST['name'])){
        $sql = "UPDATE games SET Name = ?, game_id = ?, Logo = ? WHERE game_id = ?";
        $title = $_POST['name'];
        $path = $title.'/'.$title.'.html';
        $logo = $title.'/'.$title.'.jpg';

        if($stmt = $conn->prepare($sql)){
            $stmt->bind_param("ssss", $title, $path, $logo, $game_id);

            if ($stmt->execute()) {
                if($stmt->affected_rows == 1){
                    logAdminActivity($_SESSION['admin_id'], "Updated game title from '$game_id' to '$title'.");
                }else{
                    $_SESSION['error_message'] = "Failed to Update Password  :(";
                }  
            } else {
                echo "Statement Error: " . $stmt->error;
            }
        }else {
            echo "Connection Error: " . $conn->error;
        }
    }

    //execution
    header("Location: ../viewGames.php");
    exit();
}