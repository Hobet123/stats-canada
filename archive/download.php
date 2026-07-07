<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $url = "https://www150.statcan.gc.ca/n1/tbl/csv/17100009-eng.zip"; // replace with real URL

    $saveDir = "/var/www/html/stats/downloaded/";

    $zipFile = $saveDir . "17100009-eng.zip";

    // make sure folder exists
    if (!is_dir($saveDir)) {
        mkdir($saveDir, 0777, true);
    }
    


    // 1. download file
    $fileData = file_get_contents($url);
    if ($fileData === false) {
        die("Download failed");
    }

    file_put_contents($zipFile, $fileData);
    echo "Downloaded OK\n";

    // 2. extract zip
    $zip = new ZipArchive;

    if ($zip->open($zipFile) === TRUE) {
        $zip->extractTo($saveDir);
        $zip->close();
        echo "Extracted OK\n";
    } else {
        die("Failed to open ZIP");
    }

    echo "Done\n";
