<?php
// Konfigurasi koneksi database
$host = "localhost";
$user = "root";
$password = "";
$dbname = "db_iot_bb";
$port = 3307; // Jika MySQL menggunakan port 3307

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $dbname, $port);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Queries to get the required data
$suhumaxQuery = "SELECT MAX(suhu) AS suhumax FROM tb_cuaca";
$suhuminQuery = "SELECT MIN(suhu) AS suhumin FROM tb_cuaca";
$suhurataQuery = "SELECT AVG(suhu) AS suhurata FROM tb_cuaca";
$maxHumidMaxQuery = "SELECT * FROM tb_cuaca WHERE suhu = (SELECT MAX(suhu) FROM tb_cuaca) AND humid = (SELECT MAX(humid) FROM tb_cuaca)";
$monthYearMaxQuery = "SELECT DISTINCT DATE_FORMAT(timestamp, '%m-%Y') AS month_year FROM tb_cuaca ORDER BY timestamp DESC LIMIT 2";

// Fetch results
$suhumax = $conn->query($suhumaxQuery)->fetch_assoc();
$suhumin = $conn->query($suhuminQuery)->fetch_assoc();
$suhurata = $conn->query($suhurataQuery)->fetch_assoc();
$maxHumidMax = $conn->query($maxHumidMaxQuery)->fetch_all(MYSQLI_ASSOC);
$monthYearMax = $conn->query($monthYearMaxQuery)->fetch_all(MYSQLI_ASSOC);

// Construct the JSON data
$response = [
    "suhumax" => $suhumax['suhumax'],
    "suhumin" => $suhumin['suhumin'],
    "suhurata" => $suhurata['suhurata'],
    "nilai_suhu_max_humid_max" => $maxHumidMax,
    "month_year_max" => $monthYearMax
];

// Output JSON
header('Content-Type: application/json');
echo json_encode($response);

// Close the connection
$conn->close();
?>
