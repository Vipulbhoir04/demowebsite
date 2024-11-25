<?php
// Include database connection file
include 'connection.php';
session_start();

// Ensure the user has verified OTP
if (!isset($_SESSION['email']) || !isset($_SESSION['otp'])) {
    header("Location: forgot_password.php");
    exit();
}

// Process form data when submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify the passwords match
    if ($new_password === $confirm_password) {
        $email = $_SESSION['email'];
        $sql = "UPDATE credentials SET passwordd='$new_password' WHERE email='$email'";
        if ($conn->query($sql) === TRUE) {
            $success_message = "Password has been reset successfully!";
            // Clear the OTP and email from the session
            unset($_SESSION['otp']);
            unset($_SESSION['email']);
        } else {
            $error_message = "Error: " . $conn->error;
        }
    } else {
        $error_message = "Passwords do not match.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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
        <h2 class="text-2xl font-bold mb-6 text-center">Reset Password</h2>
        <form method="POST" action="">
            <div class="floating-placeholder">
                <input type="password" name="new_password" id="new_password" placeholder=" " required>
                <label for="new_password">New Password</label>
            </div>
            <div class="floating-placeholder">
                <input type="password" name="confirm_password" id="confirm_password" placeholder=" " required>
                <label for="confirm_password">Confirm Password</label>
            </div>
            <button type="submit">Reset Password</button>
        </form>
        <?php
        if (!empty($success_message)) {
            echo '<p class="message success">' . $success_message . '</p>';
        }
        if (!empty($error_message)) {
            echo '<p class="message error">' . $error_message . '</p>';
        }
        ?>
        <a class="back-to-home" href="index.php">Login Page</a>
    </div>
</body>
</html>
