<?php
// --- SIMPLE FETCH FROM STATCAN ---

$url = "https://www150.statcan.gc.ca/t1/wds/rest/getDataFromCubePidCoordAndLatestNPeriods/17100009/40";

// 40 periods ≈ last 10 years (quarterly)

$response = file_get_contents($url);
$data = json_decode($response, true);

// Extract useful fields
$rows = [];

if (isset($data['object'])) {
    foreach ($data['object'] as $item) {
        $rows[] = [
            'date' => $item['refPer'],   // e.g. 2024-Q1
            'value' => $item['value'],  // population number
        ];
    }
}

// Reverse so oldest → newest
$rows = array_reverse($rows);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Canada Population Growth</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { border-collapse: collapse; width: 400px; }
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        th { background: #f4f4f4; }
    </style>
</head>
<body>

<h2>Canada Population (Last ~10 Years)</h2>

<table>
    <tr>
        <th>Date</th>
        <th>Population</th>
    </tr>

    <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['date']) ?></td>
            <td><?= number_format($row['value']) ?></td>
        </tr>
    <?php endforeach; ?>

</table>


</body>
</html>