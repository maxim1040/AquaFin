<?php
session_start();
require_once('config.php');

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
$material_id = intval($_POST['material_id']);
$quantity = intval($_POST['quantity']);

if ($quantity <= 0) {
    die("Aantal ongeldig.");
}

$stmt = $pdo->prepare("SELECT quantity FROM materials WHERE id = ?");
$stmt->execute([$material_id]);
$available = $stmt->fetchColumn();

if ($available === false || $available < $quantity) {
    die("Niet genoeg voorraad.");
}

$stmt = $pdo->prepare("UPDATE materials SET quantity = quantity - ? WHERE id = ?");
$stmt->execute([$quantity, $material_id]);

$stmt = $pdo->prepare("INSERT INTO orders (user_id, material_id, start_date, end_date, quantity)
                       VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 7 DAY), ?)");
$stmt->execute([$userId, $material_id, $quantity]);

header("Location: history.php");
exit;
