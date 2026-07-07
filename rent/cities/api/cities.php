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

    $stmt = $pdo->query("
        SELECT id, city_name, prov_name, prov_abbr, lat, lon
        FROM rent_cities
    ");

    echo json_encode(
        $stmt->fetchAll(PDO::FETCH_ASSOC)
    );