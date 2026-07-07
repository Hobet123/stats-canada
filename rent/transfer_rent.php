<?php

//php /home/paul/projects/stats.skill-off.met/rent/transfer_rent.php 46100092.csv rent

$filename = $argv[1] ?? null;
$tableName = $argv[2] ?? 'rent';


// echo $argv[2];
// exit;

if (!$filename || !file_exists($filename)) {
    die("Usage: php transfer.php file.csv table_name\n");
}

$handle = fopen($filename, "r");
if (!$handle) die("Cannot open file\n");

// read header
$header = fgetcsv($handle);
// $header = array_map(fn($h) => strtolower(trim($h, "\" ")), $header);
$newHeader = [];

foreach ($header as $h) {

    $h = trim($h, "\" ");

    $h = strtolower($h);

    $newHeader[] = $h;
}

$header = $newHeader;
//


fclose($handle);

foreach ($header as $i => $col) {
    $types[$i] = 'VARCHAR(255) NULL'; // default
}

// build SQL
$sql = "CREATE TABLE `$tableName` (\n    id INT AUTO_INCREMENT PRIMARY KEY,\n";

foreach ($header as $i => $col) {
    $colName = preg_replace('/[^a-z0-9_]/', '_', $col);
    $type = $types[$i] ?? 'VARCHAR(255)';
    $sql .= "    `$colName` $type NULL,\n";
}

$sql = rtrim($sql, ",\n") . "\n);";

echo $sql . "\n";


/*
CREATE TABLE `rent` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `ref_date` VARCHAR(255) NULL NULL,
    `geo` VARCHAR(255) NULL NULL,
    `dguid` VARCHAR(255) NULL NULL,
    `rental_unit_type` VARCHAR(255) NULL NULL,
    `estimates` VARCHAR(255) NULL NULL,
    `uom` VARCHAR(255) NULL NULL,
    `uom_id` VARCHAR(255) NULL NULL,
    `scalar_factor` VARCHAR(255) NULL NULL,
    `scalar_id` VARCHAR(255) NULL NULL,
    `vector` VARCHAR(255) NULL NULL,
    `coordinate` VARCHAR(255) NULL NULL,
    `value` VARCHAR(255) NULL NULL,
    `status` VARCHAR(255) NULL NULL,
    `symbol` VARCHAR(255) NULL NULL,
    `terminated` VARCHAR(255) NULL NULL,
    `decimals` VARCHAR(255) NULL NULL
);

*/



