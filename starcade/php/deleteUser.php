<?php
include 'connection.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['token'] !== $_SESSION['form_token']) {
        $_SESSION['error'] = "Invalid or duplicate submission.";
        header("Location: " . $_SERVER['HTTP_REFERER']);         
        exit();    
    }
    unset($_SESSION['form_token']);

    if (isset($_POST['username'])) {
        $username = $_POST['username'];

        $delete_user = "DELETE FROM user WHERE Username = ?";

        if ($stmt = $conn->prepare($delete_user)) {
            $stmt->bind_param("s", $username);

            if ($stmt->execute()) {
                echo "User deleted successfully.";
            } else {
                echo "Statement Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Connection Error: " . $conn->error;
        }


        $delete_scores = "DELETE FROM scores WHERE Username = ?";

        if ($stmt = $conn->prepare($delete_scores)) {
            $stmt->bind_param("s", $username);

            if ($stmt->execute()) {
                echo "scores deleted successfully.";
            } else {
                echo "Statement Error: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Connection Error: " . $conn->error;
        }
    } else {
        echo "Invalid user ID.";
    }
    $conn->close();

    
    header('Location: logout.php');
    exit();
}
?>