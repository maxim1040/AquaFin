<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['ok'=>false,'error'=>'Niet ingelogd']);
    exit;
}

$cart = json_decode(file_get_contents('php://input'), true);
if (!$cart || !is_array($cart)) {
    echo json_encode(['ok'=>false,'error'=>'Lege of ongeldige winkelmand']);
    exit;
}

try {
    $pdo->beginTransaction();

    foreach ($cart as $id => $item) {
        $id  = (int)$id;
        $qty = (int)$item['qty'];

        // Voorraad vergrendelen
        $stmt = $pdo->prepare("SELECT quantity FROM materials WHERE id=? FOR UPDATE");
        $stmt->execute([$id]);
        $stock = $stmt->fetchColumn();

        if ($stock === false)        { throw new Exception("Materiaal $id bestaat niet"); }
        if ($stock < $qty)           { throw new Exception("Onvoldoende voorraad voor ID $id"); }

        // Voorraad verminderen
        $pdo->prepare("UPDATE materials SET quantity = quantity - ? WHERE id = ?")
            ->execute([$qty, $id]);

        // Order toevoegen (7 dagen geldig)
        $pdo->prepare("
            INSERT INTO orders (user_id, material_id, start_date, end_date, quantity)
            VALUES (?, ?, CURDATE(), DATE_ADD(CURDATE(), INTERVAL 7 DAY), ?)
        ")->execute([$_SESSION['user_id'], $id, $qty]);
    }

    $pdo->commit();
    echo json_encode(['ok'=>true,'msg'=>'Bestelling geplaatst!']);
} catch (Exception $e) {
    $pdo->rollBack();
    echo json_encode(['ok'=>false,'error'=>$e->getMessage()]);
}
