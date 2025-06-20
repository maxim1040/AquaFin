<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { http_response_code(403); exit; }

$data = json_decode(file_get_contents('php://input'), true);
if (!is_array($data) || !$data) { echo 'Lege winkelmand'; exit; }

$pdo->beginTransaction();
foreach ($data as $id=>$qty){
    $id  = intval($id); $qty=intval($qty);
    // check voorraad
    $cur = $pdo->prepare("SELECT quantity FROM materials WHERE id=? FOR UPDATE");
    $cur->execute([$id]); $stock = $cur->fetchColumn();
    if($stock===false||$stock<$qty){ $pdo->rollBack(); echo 'Niet genoeg voorraad voor ID '.$id; exit; }
    // verlaag voorraad
    $pdo->prepare("UPDATE materials SET quantity = quantity-? WHERE id=?")->execute([$qty,$id]);
    // voeg order toe
    $pdo->prepare("INSERT INTO orders (user_id, material_id, start_date, end_date, quantity)
                   VALUES (?,?,NOW(),DATE_ADD(NOW(), INTERVAL 7 DAY),?)")
        ->execute([$_SESSION['user_id'],$id,$qty]);
}
$pdo->commit();
echo 'Bestelling geplaatst!';
    