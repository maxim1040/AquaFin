<?php
session_start();
include('config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Onjuiste gebruikersnaam of onjuist wachtwoord.";
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Aquafin Login</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #e8f2f9;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 400px;
        }

        h1 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 30px;
            font-size: 24px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
        }

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h1>Inloggen Aquafin</h1>
    <form method="POST">
        <label for="username">Gebruikersnaam</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Wachtwoord</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Inloggen</button>
    </form>

    <?php if (isset($error)) : ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
</div>

</body>
</html>
