<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
function sendMail ($mail){
    session_start(); 
    require __DIR__ . '/../vendor/autoload.php'; // Include PHPMailer's Composer autoload
    $config = require(__DIR__ . '/mail-config.php');
    include ("connection.php");

    if (!isset($conn) || !$conn) {
        $_SESSION['error'] = "Invalid or duplicate submission.";
        header("Location: " . $_SERVER['HTTP_REFERER']);         
        exit();
    }

    $code = random_int(100000, 999999); // generate 6-digits securely i think


    if (filter_var($mail, FILTER_VALIDATE_EMAIL)) { //mail validation
        if(isset($_SESSION['signup']) && $_SESSION['signup'] == true){
            $_SESSION['verification_code'] = $code;
            //phpmailer settings
            try {
                $mailObj = new PHPMailer(true);

                $mailObj->isSMTP();
                $mailObj->Host       = 'smtp.gmail.com';
                $mailObj->SMTPAuth   = true;
                $mailObj->Username   = $config['gmail_user'];       
                $mailObj->Password   = $config['gmail_pass'];      
                $mailObj->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption
                $mailObj->Port       = 587;
            
                $mailObj->setFrom($config['gmail_user'], 'GameSite');
                $mailObj->addAddress($mail);      
                $mailObj->Subject = "Your Verification Code";
                $mailObj->isHTML(true);
                $mailObj->Body    = "<p>Your verification code is: <strong>$code</strong></p>";
                $mailObj->AltBody = "Your verification code is: $code";
                

                $mailObj->send();

            } catch (Exception $e) {
                echo "Failed to send verification email. Mailer Error: {$mailObj->ErrorInfo}";
            }
        }else{  
            //if valid check if there's an account for that email
            $sql = "SELECT Email FROM user WHERE Email = ?  UNION SELECT Email FROM admin WHERE Email = ?";


            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ss", $mail, $mail);
        
                if ($stmt->execute()) {
                    $userresultSet = $stmt->get_result();

                    if($userresultSet->num_rows == 1){

                        $_SESSION['email'] = $mail;
                        $_SESSION['verification_code'] = $code;



                        //phpmailer settings
                        try {
                            $mailObj = new PHPMailer(true);

                            $mailObj->isSMTP();
                            $mailObj->Host       = 'smtp.gmail.com';
                            $mailObj->SMTPAuth   = true;
                            $mailObj->Username   = $config['gmail_user'];       
                            $mailObj->Password   = $config['gmail_pass'];      
                            $mailObj->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Encryption
                            $mailObj->Port       = 587;
                        
                            $mailObj->setFrom($config['gmail_user'], 'GameSite');
                            $mailObj->addAddress($mail);      
                            $mailObj->Subject = "Your Verification Code";
                            $mailObj->isHTML(true);
                            $mailObj->Body    = "<p>Your verification code is: <strong>$code</strong></p>";
                            $mailObj->AltBody = "Your verification code is: $code";
                            

                            $mailObj->send();

                        } catch (Exception $e) {
                            echo "Failed to send verification email. Mailer Error: {$mailObj->ErrorInfo}";
                        }
                    }else{
                        echo "no account with that email address.";
                    }
                } else {
                    echo "Statement Error: " . $stmt->error;
                }
            } else {
                echo "Connection Error: " . (is_object($conn) ? $conn->error : "Invalid database connection."); //ensure connection object is initiallised
            }
            $conn->close();
        }
    } else {
        echo "<p> Invalid email address. </p>";
    }
}