<?php
session_start();
require 'models.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $loginResult = loginUser($username, $password);

    if ($loginResult['statusCode'] === 200) {
        header("Location: index.php");
        exit;
    } else {
        $message = $loginResult['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #009900, #66cc66); /* Green gradient background */
            color: #fff;
            height: 100vh;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column; /* Centers content vertically */
        }

        h1 {
            font-size: 3em;
            margin-bottom: 20px;
            color: #fff;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
        }

        form {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 400px; /* Ensures form is not too wide */
        }

        input[type="text"],
        input[type="password"] {
            width: 94%;
            padding: 12px;
            margin-bottom: 15px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            background-color: #f5f5f5;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #66cc66;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #009900;
        }

        .error {
            color: #ff6666;
            font-size: 1em;
            margin-top: 10px;
            text-align: center;
        }

        a {
            color: #ffff00;
            font-size: 1.2em;
            text-decoration: none;
            margin-top: 15px;
            display: inline-block;
            text-align: center;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #ffcc00;
        }
    </style>
</head>
<body>
    <h1>Login</h1>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
    </form>
    <p class="error"><?= htmlspecialchars($message) ?></p>
    <a href="register.php">Register</a>
</body>
</html>
