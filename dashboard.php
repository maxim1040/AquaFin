<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            background: #e8f2f9;
        }

        .header {
            background: #007BFF;
            color: white;
            padding: 15px;
            text-align: center;
        }

        .menu {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin-top: 40px;
            gap: 20px;
        }

        .card {
            background: white;
            padding: 30px;
            width: 200px;
            height: 150px;
            text-align: center;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            text-decoration: none;
            color: #333;
            transition: 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }

        .logout {
            margin-top: 30px;
            text-align: center;
        }

        .logout a {
            color: red;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Welkom bij Aquafin</h1>
    <p>Hallo, <?= htmlspecialchars($role) ?> 👷</p>
</div>

<div class="menu">
    <a class="card" href="materials.php">📦<br>Bekijk voorraad</a>
    <a class="card" href="history.php">📜<br>Bestelgeschiedenis</a>
    <?php if ($role === 'admin'): ?>
        <a class="card" href="admin.php">🛠️<br>Materiaalbeheer</a>
    <?php endif; ?>
</div>

<div class="logout">
    <p><a href="logout.php">Uitloggen</a></p>
</div>

</body>
</html>
