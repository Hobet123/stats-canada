<?php

    $pdo = new PDO(
        "mysql:host=127.0.0.1;dbname=stats;charset=utf8",
        "pavel",
        "Toronto@123",
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );

    $stmt = $pdo->query("
        SELECT id, city_name
        FROM rent_cities
        LIMIT 40, 3
    ");


    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $cities = [];

    foreach ($rows as $cur) {

        $cities[$cur['id']] = $cur['city_name'];
    }

    // print_r($cities);

    // exit;

    foreach ($cities as $id => $city){

        // $city = urlencode("Québec City");

        $city = urlencode($city);

        $url = "https://nominatim.openstreetmap.org/search?q=$city&countrycodes=ca&format=json";

        $options = [
            "http" => [
                "header" => "User-Agent: MyApp/1.0\r\n"
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);

        $data = json_decode($response, true);

        $lat = $data[0]['lat'];
        $lon = $data[0]['lon'];

        $temp = explode(',', $data[0]['display_name']);

        echo  $id.",".$city.",".($temp[2]).','.$lat.','.$lon;

        echo "\r\n";
    }

    // var_dump($fordp);