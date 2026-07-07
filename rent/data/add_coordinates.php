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


    // $info = file_get_contents("data_temp.txt");

    // var_dump($info);

    foreach (file('data_temp.txt') as $line) {

        $data = [
            'id' => $line[1],
            'prov_name' => $line[2],
            'lat' => $line[3],
            'lon' => $line[4]
        ];

        print_r($data);
        exit;

        $sql = "
            UPDATE rent_city 
            SET 
                prov_name = :prov_name,
                lat = :lat,
                lon = :lon
            WHERE id = :id
        ";

        $pdo->prepare($sql)->execute($data);
    }

    //Array ( [0] => 1 [1] => Abbotsford [2] => British Columbia [3] => 49.0521162 [4] => -122.3294790 ) 

    echo "Post saved successfully!";
        
