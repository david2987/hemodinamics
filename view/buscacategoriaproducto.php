<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../model/database.php';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Connection failed: " . mysqli_connect_error());

$phrase = isset($_GET['phrase']) ? $_GET['phrase'] : '';
$sql = "SELECT CptDes, CptId FROM categoriaproductos WHERE CptDes LIKE '%" . mysqli_real_escape_string($conn, $phrase) . "%' LIMIT 20";
$queryRecords = mysqli_query($conn, $sql) or die('error to fetch data');

$result = [];
foreach($queryRecords as $res) {
    $result[] = [
        'name' => trim($res['CptDes']),
        'code' => trim($res['CptId'])
    ];
}

echo json_encode($result);
?>
