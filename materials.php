<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM materials");
$materials = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Voorraad bekijken</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Beschikbare Materialen</h1>

<ul>
<?php foreach ($materials as $material): ?>
    <li>
        <form action="order.php" method="POST" style="display:flex;gap:10px;align-items:center;">
            <?= htmlspecialchars($material['name']) ?> - Aantal: <?= $material['quantity'] ?>
            <input type="hidden" name="material_id" value="<?= $material['id'] ?>">
            <input type="number" name="quantity" value="1" min="1" max="<?= $material['quantity'] ?>" required>
            <button type="submit">Bestellen</button>
        </form>
    </li>
<?php endforeach; ?>
</ul>

<p style="text-align:center; margin-top: 40px;">
    <a href="dashboard.php" style="color:#007BFF; text-decoration:none;">⬅ Terug naar hoofdmenu</a>
</p>
</body>
</html>
