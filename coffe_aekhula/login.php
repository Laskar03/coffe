<?php
session_start();
include 'koneksi.php';

$message = '';

if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM login WHERE username='$username'";
    $result = mysqli_query($koneksi, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);

        if ($password === $user['password']) {
            $_SESSION['user'] = $user;
            header("Location: index.php");
            exit;
        } else {
            $message = "Password salah!";
        }
    } else {
        $message = "Username tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-image: url(images/gambar2.jpg);
            background-size: cover;
            background-position: center;
            color: #fff;
        }

        .login-container {
            width: 400px;
            padding: 40px;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.5);
            border-radius: 12px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        h1 {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #4CAF50;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.3);
            background: linear-gradient(45deg, #FF5722, #FFC107);
            -webkit-background-clip: text;
            color: transparent;
            position: relative;
        }

        label {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            display: block;
            text-align: left;
            margin-top: 15px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            margin-bottom: 20px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
            color: #333;
            background: #f5f7fa;
            transition: border-color 0.3s, box-shadow 0.3s;
        }

        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
            box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 8px;
            background-color: #4CAF50;
            color: #fff;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.2s;
            box-shadow: 0 5px 15px rgba(76, 175, 80, 0.3);
            position: relative;
            z-index: 2;
        }

        button:hover {
            background-color: #45a049;
            transform: translateY(-2px);
        }

        .notification {
            margin-top: 15px;
            font-weight: bold;
            color: #e63946;
            font-size: 14px;
            z-index: 2;
        }

        .footer {
            margin-top: 20px;
            font-size: 12px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Coffe Aekhula</h1>
        <form method="post" action="">
            <label>Username:</label>
            <input type="text" name="username" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit" name="login">Login</button>
        </form>
        <div class="notification"><?php echo $message; ?></div>
        <div class="footer">© 2024 Coffe Aekhula. All Rights Reserved.</div>
    </div>
</body>
</html>
