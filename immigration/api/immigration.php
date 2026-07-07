<?php

header('Content-Type: application/json');

$pdo = new PDO(
    "mysql:host=localhost;dbname=stats;charset=utf8",
    "pavel",
    "Toronto@123"
);

$type = $_GET['type'] ?? 'chart';

//
// DROPDOWN
//
if ($type === 'places') {

    $sql = "
        SELECT 
            TRIM(place_of_birth) AS place,
            MAX(CAST(value AS UNSIGNED)) AS value
        FROM stats.immigration
        WHERE place_of_birth IS NOT NULL
        AND place_of_birth != ''
        GROUP BY TRIM(place_of_birth)
        ORDER BY value DESC
        LIMIT 50 OFFSET 5
    ";

    echo json_encode(
        $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC)
    );

    exit;
}

//
// CHART DATA
//
$place = $_GET['place'] ?? '';

$sql = "
    SELECT
        immigrant_status_and_period_of_immigration,
        SUM(CAST(value AS UNSIGNED)) AS total
    FROM immigration
    WHERE TRIM(place_of_birth) = :place
    GROUP BY immigrant_status_and_period_of_immigration
    ORDER BY total DESC
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':place' => $place
]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));