<?php
include('php/connection.php');

if (isset($_GET['query'])) {
    $query = strtolower(trim($_GET['query']));
    $safeQuery = "%$query%";

    // Search games
    $sql_games = "SELECT game_id, Name FROM games WHERE Name LIKE ?";
    $stmt_games = $conn->prepare($sql_games);
    $stmt_games->bind_param("s", $safeQuery);
    $stmt_games->execute();
    $result_games = $stmt_games->get_result();

    // Search users
    $sql_users = "SELECT Username FROM user WHERE Username LIKE ? OR Email LIKE ?";
    $stmt_users = $conn->prepare($sql_users);
    $stmt_users->bind_param("ss", $safeQuery, $safeQuery);
    $stmt_users->execute();
    $result_users = $stmt_users->get_result();

    // If only one game match, redirect to game page
    if ($result_games->num_rows === 1 && $result_users->num_rows === 0) {
        $game = $result_games->fetch_assoc();
        header("Location: gamePage.php?game=" . urlencode($game['game_id']));
        exit();
    }

    // If only one user match, redirect to user profile
    if ($result_users->num_rows === 1 && $result_games->num_rows === 0) {
        $user = $result_users->fetch_assoc();
        header("Location: profile.php?user=" . urlencode($user['Username']));
        exit();
    }

    // If multiple matches, show lists
    if ($result_games->num_rows > 0) {
    echo "<h2>Game Results:</h2>";
    echo "<ul>";
    while ($game = $result_games->fetch_assoc()) {
        echo "<li><a href='gamePage.php?game=" . htmlspecialchars($game['game_id']) . "'>" . htmlspecialchars($game['Name']) . "</a></li>";
    }
    echo "</ul>";
}

if ($result_users->num_rows > 0) {
    echo "<h2>User Results:</h2>";
    echo "<ul>";
    while ($user = $result_users->fetch_assoc()) {
        echo "<li><a href='profile.php?user=" . htmlspecialchars($user['Username']) . "'>" . htmlspecialchars($user['Username']) . "</a></li>";
    }
    echo "</ul>";
}

if ($result_games->num_rows === 0 && $result_users->num_rows === 0) {
    echo "<p>No results found.</p>";
}
}
?>