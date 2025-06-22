<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];


if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);

    
    $stmt = $pdo->prepare("SELECT material_id, quantity FROM orders WHERE id = ? AND user_id = ?");
    $stmt->execute([$deleteId, $userId]);
    $order = $stmt->fetch();

    if ($order) {
        
        $pdo->prepare("UPDATE materials SET quantity = quantity + ? WHERE id = ?")
            ->execute([$order['quantity'], $order['material_id']]);

        
        $pdo->prepare("DELETE FROM orders WHERE id = ? AND user_id = ?")
            ->execute([$deleteId, $userId]);
    }

    header("Location: history.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'], $_POST['new_quantity'])) {
    $updateId = intval($_POST['update_id']);
    $newQty = intval($_POST['new_quantity']);

    if ($newQty > 0) {
        $stmt = $pdo->prepare("SELECT material_id, quantity FROM orders WHERE id = ? AND user_id = ?");
        $stmt->execute([$updateId, $userId]);
        $order = $stmt->fetch();

        if ($order) {
            $verschil = $order['quantity'] - $newQty;

            $pdo->prepare("UPDATE materials SET quantity = quantity + ? WHERE id = ?")
                ->execute([$verschil, $order['material_id']]);

            $pdo->prepare("UPDATE orders SET quantity = ? WHERE id = ? AND user_id = ?")
                ->execute([$newQty, $updateId, $userId]);
        }
    }

    header("Location: history.php");
    exit;
}

$stmt = $pdo->prepare("
    SELECT o.id, m.name AS material, o.start_date, o.end_date, o.quantity
    FROM orders o
    JOIN materials m ON o.material_id = m.id
    WHERE o.user_id = ?
    ORDER BY o.start_date DESC
");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Bestelgeschiedenis</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: sans-serif;
            background-color: #e8f2f9;
            padding: 20px;
            min-height: 100vh;
        }

        table {
            width: 100%;
            background: white;
            border-collapse: collapse;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
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

        input[type="number"] {
            width: 60px;
        }

        button {
            padding: 4px 8px;
        }

        a {
            text-decoration: none;
            color: #007BFF;
        }
        
    </style>
</head>
<body>

<p style="text-align:center; margin-top: 40px;">
    <a href="dashboard.php" style="color:#007BFF;">⬅ Terug naar hoofdmenu</a>
</p>

<h1>Bestelgeschiedenis</h1>


<?php if (count($orders) > 0): ?>
<table>
    <tr>
        <th>#</th>
        <th>Materiaal</th>
        <th>Aantal</th>
        <th>Van</th>
        <th>Tot</th>
        <th>Acties</th>
    </tr>
    <?php foreach ($orders as $order): ?>
    <tr>
        <td><?= $order['id'] ?></td>
        <td><?= htmlspecialchars($order['material']) ?></td>
        <td>
            <form method="POST" style="display:inline;">
                <input type="hidden" name="update_id" value="<?= $order['id'] ?>">
                <input type="number" name="new_quantity" value="<?= $order['quantity'] ?>" min="1" required>
                <button type="submit">💾</button>
            </form>
        </td>
        <td><?= $order['start_date'] ?></td>
        <td><?= $order['end_date'] ?></td>
        <td>
            <a href="?delete=<?= $order['id'] ?>" onclick="return confirm('Verwijderen?')">🗑️</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?php else: ?>
    <p>Je hebt nog geen bestellingen geplaatst.</p>
<?php endif; ?>



</body>
</html>
