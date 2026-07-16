<?php
header('Content-Type: application/json; charset=UTF-8');
require_once __DIR__ . '/../model/database.php';
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die("Connection failed: " . mysqli_connect_error());

$titulo = $_GET['phrase'];
$sql = "SELECT HospCod, HospDesc, HospLoc FROM hospitales WHERE HospDesc LIKE '%".mysqli_real_escape_string($conn, $titulo)."%' OR HospLoc LIKE '%".mysqli_real_escape_string($conn, $titulo)."%' LIMIT 30";
$queryRecords = mysqli_query($conn, $sql) or die('error to fetch data');

$json = '[';
foreach($queryRecords as $res):
    $display = trim($res['HospDesc']) . (trim($res['HospLoc']) ? ' - ' . trim($res['HospLoc']) : '');
    $json .= '{"name":"'.addslashes($display).'","cod_producto":"'.trim($res['HospCod']).'"},';
endforeach;
$json = rtrim($json, ',');
$json .= ']';

$json = trim($json);
$json = preg_replace("/[\r\n|\n|\r]+/", "\\n", $json);

echo $json;
?>