<?php

$file = '9810030701.csv';

$rowCount = 0;

if (($handle = fopen($file, 'r')) !== false)
{
    while (($row = fgetcsv($handle)) !== false)
    {
        $rowCount++;
    }

    fclose($handle);
}

echo "Rows: " . $rowCount;