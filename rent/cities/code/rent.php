<?php
header('Content-Type: application/json');

$host = "127.0.0.1";
$db   = "stats";
$user = "pavel";
$pass = "Toronto@123";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die(json_encode(["error" => "DB connection failed"]));
}

$geo = $_GET['geo'] ?? '';
$type = $_GET['type'] ?? '';

$sql = "
    SELECT ref_date, value
    FROM rent
    WHERE geo = ?
      AND rental_unit_type = ?
    ORDER BY ref_date ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $geo, $type);
$stmt->execute();

$result = $stmt->get_result();

$labels = [];
$values = [];

while ($row = $result->fetch_assoc()) {
    $labels[] = $row['ref_date'];
    $values[] = (int)$row['value'];
}

echo json_encode([
    "labels" => $labels,
    "values" => $values
]);