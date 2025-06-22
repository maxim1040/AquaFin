<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("
    SELECT u.username, m.name AS material, SUM(o.quantity) AS total_quantity
    FROM orders o
    JOIN users u ON o.user_id = u.id
    JOIN materials m ON o.material_id = m.id
    GROUP BY u.username, m.name
    ORDER BY u.username, m.name
");
$rows = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">

    
    <a href="dashboard.php">⬅ Terug naar dashboard</a>
    <title>Gegroepeerde Bestellingen</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #e8f2f9;
            padding: 20px;
            min-height: 100vh;
        }

        h1 {
            text-align: center;
            color: #007BFF;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin-top: 30px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #007BFF;
            color: white;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: #007BFF;
            text-decoration: none;
        }
        
    </style>
</head>
<body>

<h1>Gegroepeerde Bestellingen per Gebruiker</h1>

<?php if (count($rows) > 0): ?>
<table>
    <tr>
        <th>Gebruiker</th>
        <th>Materiaal</th>
        <th>Aantal Besteld</th>
    </tr>
    <?php foreach ($rows as $r): ?>
    <tr>
        <td><?= htmlspecialchars($r['username']) ?></td>
        <td><?= htmlspecialchars($r['material']) ?></td>
        <td><?= $r['total_quantity'] ?></td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p style="text-align:center;">Nog geen bestellingen geplaatst.</p>
<?php endif; ?>


</body>
</html>
