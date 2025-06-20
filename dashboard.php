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
            min-height: 100vh;
        }

        .header {
            background: #007BFF;
            color: white;
            padding: 15px;
            text-align: center;
            position: relative; 
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
            position: absolute;
            top: 15px;
            right: 20px;
            
        }

        .logout a {
            color: white;
            background: #dc3545;
            padding: 6px 12px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
        }

        .logout a:hover {
            background: #c82333;
        }

        h1 {
            color: white;
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Welkom bij Aquafin</h1>
    <p>Hallo, <?= htmlspecialchars($role) ?> 👷</p>

    <div class="logout">
        <p><a href="logout.php">Uitloggen</a></p>
    </div>
</div>

<div class="menu">
    <a class="card" href="materials.php">📦<br>Bekijk voorraad</a>
    <a class="card" href="history.php">📜<br>Bestelgeschiedenis</a>
    <?php if ($role === 'admin'): ?>
        <a class="card" href="admin.php">🛠️<br>Materiaalbeheer</a>
    <?php endif; ?>
</div>



</body>
</html>
