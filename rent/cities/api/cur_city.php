<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

$pdo = new PDO(
    "mysql:host=127.0.0.1;dbname=stats;charset=utf8",
    "pavel",
    "Toronto@123",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
);

// $data['id'] = 2;

$data['id'] = $_GET['id'];
$data['ref_month'] = 10;

$stmt = $pdo->prepare("
    SELECT * 
    FROM rent 
    WHERE ref_year = 2024 
      AND ref_month = :ref_month 
      AND geo_id = :id
");

$stmt->execute($data);

$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($result);

