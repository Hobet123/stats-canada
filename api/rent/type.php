<?php
header('Content-Type: application/json');

$pdo = new PDO(
    "mysql:host=127.0.0.1;dbname=stats;charset=utf8",
    "pavel",
    "Toronto@123",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$stmt = $pdo->query("
    SELECT DISTINCT TRIM(rental_unit_type) AS geo
    FROM rent_old
    WHERE geo IS NOT NULL
    ORDER BY geo
");

echo json_encode(
    $stmt->fetchAll(PDO::FETCH_COLUMN)
);