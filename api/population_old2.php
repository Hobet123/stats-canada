<?php
header('Content-Type: application/json');

try {
    $pdo = new PDO(
        "mysql:host=127.0.0.1;dbname=stats;charset=utf8",
        "pavel",
        "Toronto@123",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    $geo = isset($_GET['geo']) ? trim($_GET['geo']) : null;

    // -----------------------------------
    // 1. RETURN LIST OF ALL GEO (dropdown)
    // -----------------------------------
    if (!$geo) {
        $stmt = $pdo->query("
            SELECT DISTINCT TRIM(geo) AS geo
            FROM population
            WHERE geo IS NOT NULL AND geo != ''
            ORDER BY geo
        ");

        $geos = $stmt->fetchAll(PDO::FETCH_COLUMN);

        echo json_encode([
            'type' => 'geo_list',
            'data' => $geos
        ]);

        exit;
    }

    // -----------------------------------
    // 2. RETURN TIME SERIES FOR ONE GEO
    // -----------------------------------
    $stmt = $pdo->prepare("
        SELECT 
            ref_date,
            value
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

    // -----------------------------------
    // 3. OPTIONAL: latest value (nice UI KPI)
    // -----------------------------------
    $latest = end($series);

    echo json_encode([
        'type' => 'timeseries',
        'geo' => $geo,
        'latest' => $latest,
        'series' => $series
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Server error',
        'message' => $e->getMessage()
    ]);
}