<?php

$pdo = new PDO("mysql:host=localhost;port=3306;dbname=products_crud", 'root', 'root');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$id = $_POST['id'] ?? null;

if (!$id) {
    header('Location: index.php');
    exit;
}
$sql = "DELETE FROM products WHERE id = :id";

$stmt = $pdo->prepare($sql);
$params = [
    'id' => $id
];
$stmt->execute($params);
header('Location: index.php');
