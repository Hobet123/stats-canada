<?php

header('Content-Type: application/json');

try {

    $pdo = new PDO(
        "mysql:host=127.0.0.1;dbname=stats;charset=utf8",
        "pavel",
        "Toronto@123",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

    $geo = $_GET['geo'] ?? '';
    $component = $_GET['component'] ?? '';

    $sql = "
        SELECT
            ref_date,
            value
        FROM demographic
        WHERE geo = :geo
        AND components_of_population_growth = :component
        ORDER BY ref_date
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':geo' => $geo,
        ':component' => $component
    ]);

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($data);

} 
catch(PDOException $e) {

    echo json_encode([
        'error' => $e->getMessage()
    ]);
}