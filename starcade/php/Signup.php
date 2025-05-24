<!--STEPS
    1. CONNECT TO database
    2. CREATE A PREPARED STATEMENT
    3. ADD VALUES TO STATEMENT
    4. EXECUTE STATEMENT QUERY
    5. CLOSE CONNECTION
-->


<?php
session_start();
include ('connection.php');
include('mail-function.php');

$_SESSION['signup'] = true;
$errors = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($_POST['token'] !== $_SESSION['form_token']) {
        $_SESSION['error'] = "Invalid or duplicate submission.";
        header("Location: " . $_SERVER['HTTP_REFERER']);         
        exit();
    }

    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email = isset($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : ''; 
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';
    $confirm_password = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

    // Debugging
    //echo "Username: $username<br>";
    //echo "Email: $email<br>";

    if (empty($username)) {
        $errors[] = "Username is required.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]{3,20}$/', $username)) {
        $errors[] = "Username must be 3–20 characters long and contain only letters, numbers, and underscores.";
    }
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (empty($password) || empty($confirm_password)) {
        $errors[] = "Both password fields are required.";
    } elseif ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    } elseif (!preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $password)) {
        $errors[] = "Password must be at least 8 characters long and include both letters and numbers.";
    }


    $check_sql = "SELECT username, email FROM user WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_stmt->store_result();
    if ($check_stmt->num_rows > 0) {
        $errors[] = "An account with that username or email already exists.";
    }




    if (!empty($errors)) {
        $_SESSION['error_message'] = implode(" ", $errors);
        header("Location: ../RegisterationForm.php");
        exit();
    } else {
        sendMail($email);
        $_SESSION['pending_user'] = [
            'username' => $username,
            'email' => $email,
            'password' => $password
        ];
        header("Location: ../verifyEmail.php");  //Redirect to verification page
        exit(); 
    }
}
?>
