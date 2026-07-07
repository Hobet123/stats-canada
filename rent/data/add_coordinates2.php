<?php


    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $pdo = new PDO(
        "mysql:host=127.0.0.1;dbname=stats;charset=utf8",
        "pavel",
        "Toronto@123",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

    $file = 'data_temp.txt';

    if (!file_exists($file)) {
        die("File not found");
    }

    $sql = "
        UPDATE rent_cities 
        SET 
            prov_name = :prov_name,
            lat = :lat,
            lon = :lon
        WHERE id = :id
    ";

    $stmt = $pdo->prepare($sql);

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {

        // clean line
        $line = trim($line);

        // skip bad debug lines (like //Array ...)
        if (strpos($line, 'Array') !== false || strpos($line, '//') === 0) {
            continue;
        }

        $temp = array_map('trim', explode(',', $line));

        // must have 5 columns
        if (count($temp) < 5) {
            echo "SKIPPED: $line\n";
            continue;
        }

        $data = [
            'id' => $temp[0],
            'prov_name' => $temp[2],
            'lat' => $temp[3],
            'lon' => $temp[4]
        ];

        $stmt->execute($data);

        echo "ID {$data['id']} -> rows affected: " . $stmt->rowCount() . PHP_EOL;
    }

    echo "DONE\n";