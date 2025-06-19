<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['name'], $_POST['quantity']) && !isset($_POST['update_id'])) {
    $name = $_POST['name'];
    $quantity = intval($_POST['quantity']);
    $stmt = $pdo->prepare("INSERT INTO materials (name, quantity) VALUES (?, ?)");
    $stmt->execute([$name, $quantity]);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_id'], $_POST['new_quantity'])) {
    $id = intval($_POST['update_id']);
    $newQty = intval($_POST['new_quantity']);
    $pdo->prepare("UPDATE materials SET quantity = ? WHERE id = ?")->execute([$newQty, $id]);
}

if (isset($_GET['delete_id'])) {
    $materialId = $_GET['delete_id'];
    $pdo->prepare("DELETE FROM orders WHERE material_id = ?")->execute([$materialId]);
    $pdo->prepare("DELETE FROM materials WHERE id = ?")->execute([$materialId]);
}

$stmt = $pdo->query("SELECT * FROM materials");
$materials = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin - Materialen Beheren</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .materiaal-lijst {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .materiaal-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            align-items: center;
        }
        form input[type="number"] {
            width: 60px;
        }
    </style>
</head>
<body>

<h1>Beheer Materialen</h1>

<div class="materiaal-lijst">
    <form method="POST">
        <label for="name">Materiaalnaam:</label>
        <input type="text" name="name" id="name" required>
        <br>
        <label for="quantity">Aantal:</label>
        <input type="number" name="quantity" id="quantity" required>
        <br><br>
        <button type="submit">➕ Toevoegen</button>
    </form>
</div>

<h2 style="text-align:center;">Materiaal Lijst</h2>

<div class="materiaal-lijst">
    <?php foreach ($materials as $material): ?>
        <div class="materiaal-item">
            <strong><?= htmlspecialchars($material['name']) ?></strong>
            <form method="POST" style="display: inline-flex; gap: 5px;">
                <input type="hidden" name="update_id" value="<?= $material['id'] ?>">
                <input type="number" name="new_quantity" value="<?= $material['quantity'] ?>" min="0" required>
                <button type="submit">💾</button>
            </form>
            <a href="admin.php?delete_id=<?= $material['id'] ?>" onclick="return confirm('Weet je zeker dat je dit materiaal (en bestellingen) wilt verwijderen?')" style="color:red;">🗑️</a>
        </div>
    <?php endforeach; ?>
</div>

<p style="text-align:center; margin-top: 40px;">
    <a href="dashboard.php" style="color:#007BFF; text-decoration:none;">⬅ Terug naar hoofdmenu</a>
</p>

<div class="logout" style="text-align: center; margin-top: 20px;">
    <a href="logout.php" style="color: red;">Uitloggen</a>
</div>

</body>
</html>
