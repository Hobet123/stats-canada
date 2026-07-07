<?php
header('Content-Type: application/json');

$pdo = new PDO(
    "mysql:host=127.0.0.1;dbname=stats;charset=utf8",
    "pavel",
    "Toronto@123",
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]
);

$geo = $_GET['geo'] ?? 'Canada';

$stmt = $pdo->prepare("
    SELECT ref_date, value
    FROM population
    WHERE geo = :geo
    ORDER BY ref_date ASC
");

$stmt->execute(['geo' => $geo]);

$data = [];

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[] = [
        'date' => $row['ref_date'],
        'value' => (int)$row['value']
    ];
}

echo json_encode([
    'geo' => $geo,
    'series' => $data
]);