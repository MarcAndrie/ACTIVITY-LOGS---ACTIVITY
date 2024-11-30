<?php
session_start();
require 'models.php';

// Initialize variables
$message = '';
$status = 200;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $result = registerUser($username, $password);
        $message = $result['message'];
        $status = $result['statusCode'];
    } else {
        $message = 'Please fill out all fields.';
        $status = 400;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 300px;
            text-align: center;
        }
        input {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            color: white;
            font-size: 14px;
        }
        .message-success {
            background-color: #4CAF50;
        }
        .message-error {
            background-color: #f44336;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>
        <!-- Display Messages -->
        <?php if ($message): ?>
            <div class="message <?= $status === 200 ? 'message-success' : 'message-error' ?>">
                <?= $message ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <p><a href="login.php">Back to Login</a></p>
    </div>
</body>
</html>
