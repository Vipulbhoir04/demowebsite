<?php
// Include database connection file and PHPMailer
include 'connection.php';
session_start(); // Start the session
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

// Process form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $sql = "SELECT * FROM credentials WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);

        // Save the OTP and email in session
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        // Send OTP to user's email
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';  // Specify your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'vipulbhoir027@gmail.com';  // Your email address
        $mail->Password = 'znbkspsizlrnliwh';  // Your email password
        $mail->SMTPSecure = 'tls'; 
        $mail->Port = 587; 

        $mail->setFrom('vipulbhoir027@gmail.com', 'Website Demo');
        $mail->addAddress($email);
        $mail->Subject = 'Password Reset OTP';
        $mail->Body    = "Your OTP for password reset is: $otp";

        if ($mail->send()) {
            // Redirect to reset password page
            header("Location: verify_otp.php");
            exit();
        } else {
            $error_message = "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        $error_message = "No account found with that email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1a1a1a, #333333, #4d4d4d);
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            width: 400px;
            padding: 20px;
            background: #2d2d2d;
            border-radius: 0.25rem;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }
        .floating-placeholder {
            position: relative;
            margin-bottom: 20px;
        }
        .floating-placeholder input {
            border: 1px solid #333;
            padding: 1rem 0.5rem 0.5rem 0.5rem;
            border-radius: 0.25rem;
            background: transparent;
            color: white;
            width: 100%;
        }
        .floating-placeholder label {
            position: absolute;
            top: 1rem;
            left: 0.5rem;
            color: #b3b3b3;
            padding: 0 0.25rem;
            transition: all 0.2s ease;
            pointer-events: none;
        }
        .floating-placeholder input:focus + label,
        .floating-placeholder input:not(:placeholder-shown) + label {
            top: -0.75rem;
            left: 0.5rem;
            font-size: 1rem;
            color: #1e90ff;
            background: #1a1a1a;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #1e90ff;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover {
            background: #005bb5;
        }
        .message {
            margin-top: 20px;
            text-align: center;
        }
        .success {
            color: #4caf50;
        }
        .error {
            color: #f44336;
        }
        .back-to-home {
            margin-top: 20px;
            display: block;
            text-align: center;
            color: #1e90ff;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-2xl font-bold mb-6 text-center">Forgot Password</h2>
        <form method="POST" action="">
            <div class="floating-placeholder">
                <input type="email" name="email" id="email" placeholder=" " required>
                <label for="email">Email</label>
            </div>
            <button type="submit">Send OTP</button>
        </form>
        <?php
        if (!empty($error_message)) {
            echo '<p class="message error">' . $error_message . '</p>';
        }
        ?>
        <a class="back-to-home" href="home.php">Back to Home</a>
    </div>
</body>
</html>
