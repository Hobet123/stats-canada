<?php
header('Content-Type: application/json');

$pdo = new PDO(
    "mysql:host=127.0.0.1;dbname=stats;charset=utf8",
    "pavel",
    "Toronto@123",
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

$geos = isset($_GET['geo']) ? explode(',', $_GET['geo']) : [];

if (count($geos) === 0) {
    echo json_encode(['error' => 'No geo provided']);
    exit;
}

$result = [];

foreach ($geos as $geo) {
    $geo = trim($geo);

    $stmt = $pdo->prepare("
        SELECT ref_date, value
        FROM population
        WHERE TRIM(geo) = :geo
        ORDER BY ref_date ASC
    ");

    $stmt->execute(['geo' => $geo]);

    $series = [];

    while ($row = $stmt->fetch()) {
        $series[] = [
            'date' => $row['ref_date'],
            'value' => (int)$row['value']
        ];
    }

    $result[] = [
        'geo' => $geo,
        'series' => $series
    ];
}

echo json_encode([
    'type' => 'compare',
    'data' => $result
]);