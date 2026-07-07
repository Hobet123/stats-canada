<?php

//php script.php 17100009.csv population_test

$filename = $argv[1] ?? null;
$tableName = $argv[2] ?? 'generated_table';

if (!$filename || !file_exists($filename)) {
    die("Usage: php script.php file.csv table_name\n");
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

// sample rows
$sampleSize = 5;
$rows = [];

for ($i = 0; $i < $sampleSize && ($row = fgetcsv($handle)); $i++) {
    $rows[] = $row;
}

fclose($handle);

// detect column types
$types = [];

foreach ($header as $i => $col) {
    $types[$i] = 'INT'; // default
}

foreach ($rows as $row) { 

    foreach ($row as $i => $value) {

        $value = trim($value);

        if ($value === '' || strtolower($value) === 'null') {
            continue;
        }

        if (!isset($types[$i]) || $types[$i] === 'VARCHAR(255)') {
            continue;
        }

        if (is_numeric($value)) {
            
            if (strpos($value, '.') !== false) {
                $types[$i] = 'DECIMAL(12,2)';
            } 
            else {
                $types[$i] = 'INT';
            }
        } 
        else {
            // try date detection
            if (strtotime($value) !== false) {
                $types[$i] = 'DATE';
            } 
            else {
                $len = max(strlen($value), 10);
                $types[$i] = "VARCHAR(255)";
            }
        }
    }
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