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

    $stmt = $pdo->query("
        SELECT geo, property_type, bedrooms, rent_price, ref_year, ref_month, geo_id
        FROM rent
        WHERE ref_year=2025 and ref_month=10
    ");

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $grouped = [];

    foreach ($data as $row) {
        $city = $row['geo'];

        if (!isset($grouped[$city])) {
            $grouped[$city] = [
                'geo_id' => $row['geo_id'],
                'geo' => $city,
                'types' => []
            ];
        }

        $grouped[$city]['types'][] = [
            'property_type' => $row['property_type'],
            'bedrooms' => $row['bedrooms'],
            'rent_price' => $row['rent_price'],
        ];
    }

    echo json_encode(array_values($grouped));