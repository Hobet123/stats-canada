<?php

//php /home/paul/projects/stats.skill-off.met/data/immigration/transfer_dem.php 17100008.csv demographic

$filename = $argv[1] ?? null;
$tableName = $argv[2] ?? 'demographic';

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

// print_r($header);

// sample rows
// $sampleSize = 5;
// $rows = [];

// for ($i = 0; $i < $sampleSize && ($row = fgetcsv($handle)); $i++) {
//     $rows[] = $row;
// }

// $row1 = fgetcsv($handle);
// print_r($row1);
// $row2 = fgetcsv($handle);
// print_r($row2);



// for ($i = 1; ($row = fgetcsv($handle)) !== false; $i++) {

//     if ($i == 30) {
//         print_r($row);
//         break;
//     }
// }

fclose($handle);

foreach ($header as $i => $col) {
    $types[$i] = 'VARCHAR(255)'; // default
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

CREATE TABLE `demographic` (
    id INT AUTO_INCREMENT PRIMARY KEY,
    `ref_date` VARCHAR(255) NULL,
    `geo` VARCHAR(255) NULL,
    `dguid` VARCHAR(255) NULL,
    `components_of_population_growth` VARCHAR(255) NULL,
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

*/


