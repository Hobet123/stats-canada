<?php

//php transfer_imm.php 9810030701.csv immigration

$filename = $argv[1] ?? null;
$tableName = $argv[2] ?? 'immigration';

if (!$filename || !file_exists($filename)) {
    die("Usage: php transfer.php file.csv table_name\n");
}

$handle = fopen($filename, "r");
if (!$handle) die("Cannot open file\n");

// read header
$header = fgetcsv($handle);

$newHeader = [];

foreach ($header as $h) {

    $h = trim($h, "\" ");

    $h = strtolower($h);

    $newHeader[] = $h;
}

$header = $newHeader;

fclose($handle);

foreach ($header as $i => $col) {
    $types[$i] = 'VARCHAR(255)'; // default
}

// build SQL
$sql = "CREATE TABLE `$tableName` (\n    id INT AUTO_INCREMENT PRIMARY KEY,\n";

foreach ($header as $i => $col) {
    $colName = preg_replace('/[^a-z0-9_]/', '_', $col);
    $type = $types[$i] ?? 'VARCHAR(255) NULL';
    $sql .= "    `$colName` $type NULL,\n";
}

$sql = rtrim($sql, ",\n") . "\n);";

echo $sql . "\n";

/*

CREATE TABLE `immigration` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `ref_date` VARCHAR(255) NULL,
    `geo` VARCHAR(255) NULL,
    `dguid` VARCHAR(255) NULL,
    `age` VARCHAR(255) NULL,
    `gender` VARCHAR(255) NULL,
    `place_of_birth` VARCHAR(255) NULL,
    `immigrant_status_and_period_of_immigration` VARCHAR(255) NULL,
    `uom` VARCHAR(255) NULL,
    `uom_id` VARCHAR(255) NULL,
    `scalar_factor` VARCHAR(255) NULL,
    `scalar_id` VARCHAR(255) NULL,
    `vector` VARCHAR(255) NULL,
    `coordinate` VARCHAR(255) NULL,
    `value` VARCHAR(255) NULL,
    `status` VARCHAR(255) NULL,
    `symbol` VARCHAR(255) NULL,
    `terminated1` VARCHAR(255) NULL,
    `decimals` VARCHAR(255) NULL
);



ALTER TABLE immigration
MODIFY value DECIMAL(15,2) NULL;

CREATE INDEX idx_demo
ON demographic (geo, components_of_population_growth, ref_date);

*/


