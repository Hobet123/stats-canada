<?php

    // DB CONFIG
    $host = '127.0.0.1';
    $db   = 'stats';
    $user = 'pavel';
    $pass = 'Toronto@123';
    $charset = 'utf8mb4';

    // CSV FILE
    $file = '/var/www/html/stats/downloaded/17100009.csv';

    // CONNECT
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        die("DB ERROR: " . $e->getMessage());
    }

    // PREPARE INSERT
    $sql = "INSERT INTO population (
        ref_date, geo, dguid, uom, uom_id,
        scalar_factor, scalar_id, vector, coordinate,
        value, status, symbol, terminated1, decimals
    ) VALUES (
        :ref_date, :geo, :dguid, :uom, :uom_id,
        :scalar_factor, :scalar_id, :vector, :coordinate,
        :value, :status, :symbol, :terminated1, :decimals
    )";
    $stmt = $pdo->prepare($sql);

    // OPEN CSV
    if (!file_exists($file)) {
        die("File not found");
    }

    $handle = fopen($file, 'r');

    // SKIP HEADER
    fgetcsv($handle);

    $count = 0;

    while (($row = fgetcsv($handle)) !== false) {

        $stmt->execute([
            ':ref_date'      => $row[0],
            ':geo'           => $row[1],
            ':dguid'         => $row[2],
            ':uom'           => $row[3],
            ':uom_id'        => $row[4],
            ':scalar_factor' => $row[5],
            ':scalar_id'     => $row[6],
            ':vector'        => $row[7],
            ':coordinate'    => $row[8],
            ':value'         => $row[9] === '' ? null : $row[9],
            ':status'        => $row[10] ?: null,
            ':symbol'        => $row[11] ?: null,
            ':terminated1'   => $row[12] ?: null,
            ':decimals'      => $row[13],
        ]);

        $count++;

        if ($count % 1000 === 0) {
            echo "Inserted: $count\n";
        }
    }

    fclose($handle);

    echo "DONE. Total inserted: $count\n";