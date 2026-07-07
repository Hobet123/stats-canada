<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

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

// echo $geo;
// echo "<br>";
// echo $type;
// echo "<br>";

$sql = "
    SELECT ref_date, rent_price
    FROM rent_old
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
    $values[] = (int)$row['rent_price'];
}

echo json_encode([
    "labels" => $labels,
    "values" => $values
]);

//https://stats.skill-off.me/api/rent/rent.php?geo=Toronto&type=Apartment%20-%202%20bedroom